<?php

namespace App\Services;

use App\Models\Hr\Employee;
use App\Models\Hr\FaceEnrollment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    private string $pythonScript;
    private string $modelsPath;
    private float $confidenceThreshold = 0.85;

    public function __construct()
    {
        $this->pythonScript = storage_path('app/face_recognition/face_processor.py');
        $this->modelsPath = storage_path('app/face_recognition/models');
        
        // Ensure directories exist
        if (!is_dir($this->modelsPath)) {
            mkdir($this->modelsPath, 0755, true);
        }
    }

    public function enrollFace(UploadedFile $image, Employee $employee): array
    {
        try {
            // Store the uploaded image temporarily
            $tempImagePath = $image->store('temp/face_enrollment');
            $fullTempPath = Storage::path($tempImagePath);
            
            // Create unique paths for this enrollment
            $encodingPath = "face_encodings/{$employee->id}_" . time() . ".json";
            $sampleImagePath = "face_samples/{$employee->id}_" . time() . ".jpg";
            
            $fullEncodingPath = Storage::path($encodingPath);
            $fullSamplePath = Storage::path($sampleImagePath);
            
            // Ensure directories exist
            Storage::makeDirectory('face_encodings');
            Storage::makeDirectory('face_samples');
            
            // Call Python script to process the face
            $command = "python3 {$this->pythonScript} enroll " . 
                      "--input " . escapeshellarg($fullTempPath) . " " .
                      "--encoding-output " . escapeshellarg($fullEncodingPath) . " " .
                      "--sample-output " . escapeshellarg($fullSamplePath);
            
            $output = [];
            $returnCode = 0;
            exec($command . " 2>&1", $output, $returnCode);
            
            // Clean up temp file
            Storage::delete($tempImagePath);
            
            if ($returnCode === 0) {
                $result = json_decode(implode('', $output), true);
                
                if ($result && $result['success']) {
                    // Deactivate old enrollments
                    $employee->faceEnrollments()
                            ->where('status', 'active')
                            ->update(['status' => 'inactive']);
                    
                    // Create new enrollment record
                    FaceEnrollment::create([
                        'employee_id' => $employee->id,
                        'encoding_file_path' => $encodingPath,
                        'sample_image_path' => $sampleImagePath,
                        'quality_score' => $result['quality_score'] ?? 0,
                        'face_landmarks' => $result['landmarks'] ?? [],
                        'status' => 'active',
                        'enrolled_by' => auth()->id()
                    ]);
                    
                    return [
                        'success' => true,
                        'encoding_path' => $encodingPath,
                        'quality_score' => $result['quality_score'] ?? 0
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => 'Failed to process face: ' . implode(' ', $output)
            ];
            
        } catch (\Exception $e) {
            Log::error('Face enrollment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Face enrollment failed: ' . $e->getMessage()
            ];
        }
    }

    public function recognizeFace(UploadedFile $image): array
    {
        try {
            // Store the uploaded image temporarily
            $tempImagePath = $image->store('temp/face_recognition');
            $fullTempPath = Storage::path($tempImagePath);
            
            // Get all active enrollments
            $activeEnrollments = FaceEnrollment::where('status', 'active')
                                             ->with('employee')
                                             ->get();
            
            if ($activeEnrollments->isEmpty()) {
                Storage::delete($tempImagePath);
                return [
                    'success' => false,
                    'message' => 'No enrolled faces found'
                ];
            }
            
            $bestMatch = null;
            $highestConfidence = 0;
            
            foreach ($activeEnrollments as $enrollment) {
                if (!Storage::exists($enrollment->encoding_file_path)) {
                    continue;
                }
                
                $encodingPath = Storage::path($enrollment->encoding_file_path);
                
                // Call Python script to compare faces
                $command = "python3 {$this->pythonScript} recognize " .
                          "--input " . escapeshellarg($fullTempPath) . " " .
                          "--encoding " . escapeshellarg($encodingPath);
                
                $output = [];
                $returnCode = 0;
                exec($command . " 2>&1", $output, $returnCode);
                
                if ($returnCode === 0) {
                    $result = json_decode(implode('', $output), true);
                    
                    if ($result && isset($result['confidence']) && 
                        $result['confidence'] > $highestConfidence && 
                        $result['confidence'] >= $this->confidenceThreshold) {
                        
                        $highestConfidence = $result['confidence'];
                        $bestMatch = $enrollment->employee;
                    }
                }
            }
            
            // Clean up temp file
            Storage::delete($tempImagePath);
            
            if ($bestMatch) {
                return [
                    'success' => true,
                    'employee' => $bestMatch,
                    'confidence' => $highestConfidence
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No matching face found with sufficient confidence'
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Face recognition error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Face recognition failed: ' . $e->getMessage()
            ];
        }
    }

    public function setConfidenceThreshold(float $threshold): void
    {
        $this->confidenceThreshold = max(0.5, min(1.0, $threshold));
    }

    public function getConfidenceThreshold(): float
    {
        return $this->confidenceThreshold;
    }
}
