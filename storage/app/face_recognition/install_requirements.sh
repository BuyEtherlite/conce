
#!/bin/bash

# Install Python face recognition dependencies
echo "Installing Python dependencies for face recognition..."

# Update system packages
sudo apt-get update

# Install system dependencies for dlib and opencv
sudo apt-get install -y python3-pip python3-dev
sudo apt-get install -y libopenblas-dev liblapack-dev libatlas-base-dev
sudo apt-get install -y libgtk-3-dev libboost-python-dev
sudo apt-get install -y cmake

# Install Python packages
pip3 install --upgrade pip
pip3 install -r requirements.txt

echo "Face recognition dependencies installed successfully!"
echo "Make sure to run: chmod +x face_processor.py"
