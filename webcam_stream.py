import cv2
import requests
from face_detection import crop_faces
from time import time

API_URL = "http://localhost/api/sensors.php"
CASCADE_PATH = "api/files/haarcascade_frontalface_default.xml"

CAPTURE_DESTINATION = "api/files/webcam.jpg"

# initilize camera
capture = cv2.VideoCapture(0)

try:
    while True:
        # capture frame
        ret, frame = capture.read()

        cv2.imwrite("files/webcam.jpg", frame)

        # display frame
        cv2.imshow("Parking Camera", frame)

        faceCascade = cv2.CascadeClassifier(CASCADE_PATH)
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

        faces = faceCascade.detectMultiScale(
            gray,
            scaleFactor=1.1,
            minNeighbors=5,
            minSize=(30,30),
            flags=cv2.CASCADE_SCALE_IMAGE
        )

        print(faces)

        are_faces_present = int(len(faces) > 0)
        data = {"name": "face_detection", "value": are_faces_present}

        r = requests.post(API_URL, data=data)

        key = cv2.waitKey(50)

        if key % 256 == 27:
            break

except Exception as e:
    print("Ocorreu um erro." + e.message)

finally:
    capture.release()
    cv2.destroyAllWindows()
    print("Execução terminada.")

