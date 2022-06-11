import cv2
import requests
import sqlite3
from face_detection import crop_faces
from time import time
import os

API_URL = "http://localhost/capture_webcam.php"
DATABASE_PATH = "api/files/projeto.db"
CASCADE_PATH = "api/files/haarcascade_frontalface_default.xml"

CAPTURE_DESTINATION = "api/files/webcam.jpg"

# initilize camera
capture = cv2.VideoCapture(0)

# connect to database
con = sqlite3.connect(DATABASE_PATH)
cur = con.cursor()

try:
    while True:
        # capture frame
        ret, frame = capture.read()

        cv2.imwrite("files/webcam.jpg", frame)

        files = {'frame': ('frame.png', open(CAPTURE_DESTINATION, 'rb'))}

        requests.post(API_URL, files=files)

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

        cur.execute("""UPDATE SensorsActuators SET value = ?, TIME = ? WHERE name = ?""", (len(faces) > 0, int(time()), "face_detection"))
        con.commit()

        key = cv2.waitKey(50)

        if key % 256 == 27:
            break

except Exception as e:
    print("Ocorreu um erro." + e.message)

finally:
    capture.release()
    cv2.destroyAllWindows()
    cur.close()
    con.close()
    print("Execução terminada.")

