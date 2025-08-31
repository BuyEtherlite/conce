
#!/usr/bin/env python3
import face_recognition
import cv2
import numpy as np
import json
import argparse
import sys
import os
from PIL import Image

def preprocess_image(image_path):
    """Preprocess image for better face recognition"""
    try:
        # Load image
        image = cv2.imread(image_path)
        if image is None:
            raise ValueError("Could not load image")
        
        # Convert to RGB
        image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
        
        # Enhance image quality
        # Adjust brightness and contrast
        alpha = 1.2  # Contrast control
        beta = 10    # Brightness control
        image = cv2.convertScaleAbs(image, alpha=alpha, beta=beta)
        
        # Reduce noise
        image = cv2.bilateralFilter(image, 9, 75, 75)
        
        return image
    except Exception as e:
        raise Exception(f"Image preprocessing failed: {str(e)}")

def enroll_face(input_path, encoding_output, sample_output):
    """Enroll a new face"""
    try:
        # Preprocess image
        image = preprocess_image(input_path)
        
        # Find face locations
        face_locations = face_recognition.face_locations(image, model="hog")
        
        if len(face_locations) == 0:
            return {
                "success": False,
                "message": "No face detected in the image"
            }
        
        if len(face_locations) > 1:
            return {
                "success": False,
                "message": "Multiple faces detected. Please use an image with only one face."
            }
        
        # Get face encodings
        face_encodings = face_recognition.face_encodings(image, face_locations)
        
        if len(face_encodings) == 0:
            return {
                "success": False,
                "message": "Could not encode the face"
            }
        
        face_encoding = face_encodings[0]
        face_location = face_locations[0]
        
        # Calculate quality score based on face size and clarity
        top, right, bottom, left = face_location
        face_width = right - left
        face_height = bottom - top
        face_area = face_width * face_height
        
        # Quality score based on face size (larger faces generally better)
        image_area = image.shape[0] * image.shape[1]
        size_ratio = face_area / image_area
        quality_score = min(100, max(50, size_ratio * 1000))
        
        # Get face landmarks
        face_landmarks = face_recognition.face_landmarks(image, face_locations)
        landmarks_data = face_landmarks[0] if face_landmarks else {}
        
        # Save encoding
        encoding_data = {
            "encoding": face_encoding.tolist(),
            "location": face_location,
            "landmarks": landmarks_data,
            "quality_score": quality_score
        }
        
        with open(encoding_output, 'w') as f:
            json.dump(encoding_data, f)
        
        # Save sample image (cropped face)
        face_image = image[top:bottom, left:right]
        face_pil = Image.fromarray(face_image)
        face_pil.save(sample_output, "JPEG", quality=95)
        
        return {
            "success": True,
            "quality_score": quality_score,
            "landmarks": landmarks_data,
            "face_location": face_location
        }
        
    except Exception as e:
        return {
            "success": False,
            "message": str(e)
        }

def recognize_face(input_path, encoding_path):
    """Recognize a face against stored encoding"""
    try:
        # Load stored encoding
        with open(encoding_path, 'r') as f:
            stored_data = json.load(f)
        
        stored_encoding = np.array(stored_data["encoding"])
        
        # Preprocess input image
        image = preprocess_image(input_path)
        
        # Find face locations
        face_locations = face_recognition.face_locations(image, model="hog")
        
        if len(face_locations) == 0:
            return {
                "success": False,
                "confidence": 0,
                "message": "No face detected"
            }
        
        # Get face encodings
        face_encodings = face_recognition.face_encodings(image, face_locations)
        
        if len(face_encodings) == 0:
            return {
                "success": False,
                "confidence": 0,
                "message": "Could not encode face"
            }
        
        # Compare with stored encoding
        best_confidence = 0
        
        for face_encoding in face_encodings:
            # Calculate distance (lower is better)
            distance = face_recognition.face_distance([stored_encoding], face_encoding)[0]
            
            # Convert distance to confidence percentage
            confidence = max(0, (1 - distance) * 100)
            
            if confidence > best_confidence:
                best_confidence = confidence
        
        return {
            "success": True,
            "confidence": best_confidence
        }
        
    except Exception as e:
        return {
            "success": False,
            "confidence": 0,
            "message": str(e)
        }

def main():
    parser = argparse.ArgumentParser(description='Face Recognition Processor')
    subparsers = parser.add_subparsers(dest='command', help='Commands')
    
    # Enroll command
    enroll_parser = subparsers.add_parser('enroll', help='Enroll a new face')
    enroll_parser.add_argument('--input', required=True, help='Input image path')
    enroll_parser.add_argument('--encoding-output', required=True, help='Encoding output path')
    enroll_parser.add_argument('--sample-output', required=True, help='Sample image output path')
    
    # Recognize command
    recognize_parser = subparsers.add_parser('recognize', help='Recognize a face')
    recognize_parser.add_argument('--input', required=True, help='Input image path')
    recognize_parser.add_argument('--encoding', required=True, help='Stored encoding path')
    
    args = parser.parse_args()
    
    if args.command == 'enroll':
        result = enroll_face(args.input, args.encoding_output, args.sample_output)
    elif args.command == 'recognize':
        result = recognize_face(args.input, args.encoding)
    else:
        result = {"success": False, "message": "Unknown command"}
    
    print(json.dumps(result))

if __name__ == "__main__":
    main()
