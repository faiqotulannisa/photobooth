import cv2
import os
import time
import datetime
import numpy as np

# ================= CONFIG =================
CAM_W, CAM_H = 640, 480
TOTAL_PHOTOS = 3
COUNTDOWN = 3
SAVE_DIR = "photos"
FRAME_PATH = "frame.png"
LOGO_PATH = "logo.png"
EVENT_TEXT = "MY EVENT 2026"
WINDOW = "PHOTOBOX PRO"
# =========================================

os.makedirs(SAVE_DIR, exist_ok=True)

cap = cv2.VideoCapture(0, cv2.CAP_DSHOW)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, CAM_W)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, CAM_H)

frame_overlay = cv2.imread(FRAME_PATH, cv2.IMREAD_UNCHANGED)
frame_overlay = cv2.resize(frame_overlay, (CAM_W, CAM_H))

logo = cv2.imread(LOGO_PATH, cv2.IMREAD_UNCHANGED)
logo = cv2.resize(logo, (120, 120))

cv2.namedWindow(WINDOW, cv2.WINDOW_NORMAL)
cv2.resizeWindow(WINDOW, 1000, 720)

captured = []

def apply_png(bg, fg, x=0, y=0):
    if fg.shape[2] == 4:
        alpha = fg[:, :, 3] / 255.0
        for c in range(3):
            bg[y:y+fg.shape[0], x:x+fg.shape[1], c] = (
                bg[y:y+fg.shape[0], x:x+fg.shape[1], c] * (1 - alpha) +
                fg[:, :, c] * alpha
            )

def draw_button(img, text, x, y, w, h):
    cv2.rectangle(img, (x,y), (x+w,y+h), (50,50,50), -1)
    cv2.rectangle(img, (x,y), (x+w,y+h), (0,255,0), 2)
    cv2.putText(img, text, (x+20, y+40),
                cv2.FONT_HERSHEY_SIMPLEX, 1,
                (0,255,0), 2)

def countdown(frame, n):
    cv2.putText(frame, str(n),
                (CAM_W//2-40, CAM_H//2+40),
                cv2.FONT_HERSHEY_SIMPLEX, 4,
                (0,0,255), 8)

def make_strip(images):
    strip = np.vstack(images)
    strip = cv2.resize(strip, (600, 1800))
    return strip

def mouse_event(event, x, y, flags, param):
    global start_pressed, quit_pressed
    if event == cv2.EVENT_LBUTTONDOWN:
        if 700 < x < 950 and 500 < y < 560:
            start_pressed = True
        if 700 < x < 950 and 580 < y < 640:
            quit_pressed = True

cv2.setMouseCallback(WINDOW, mouse_event)

start_pressed = False
quit_pressed = False

while True:
    ret, frame = cap.read()
    if not ret:
        break

    display = frame.copy()
    apply_png(display, frame_overlay)
    apply_png(display, logo, 20, 20)

    cv2.putText(display, EVENT_TEXT, (160, 80),
                cv2.FONT_HERSHEY_SIMPLEX, 1.2,
                (255,255,255), 3)

    if captured:
        preview = make_strip(captured)
        preview = cv2.resize(preview, (300, 900))
        display[0:preview.shape[0], 650:950] = preview

    draw_button(display, "START", 700, 500, 250, 60)
    draw_button(display, "EXIT", 700, 580, 250, 60)

    cv2.imshow(WINDOW, display)
    cv2.waitKey(1)

    # ========= START PHOTO =========
    if start_pressed:
        start_pressed = False
        captured = []

        session = datetime.datetime.now().strftime("%Y%m%d_%H%M%S")
        folder = os.path.join(SAVE_DIR, session)
        os.makedirs(folder)

        for i in range(TOTAL_PHOTOS):
            for c in range(COUNTDOWN, 0, -1):
                ret, f = cap.read()
                temp = f.copy()
                apply_png(temp, frame_overlay)
                countdown(temp, c)
                cv2.imshow(WINDOW, temp)
                cv2.waitKey(1000)

            ret, f = cap.read()
            final = f.copy()
            apply_png(final, frame_overlay)
            apply_png(final, logo, 20, 20)

            path = os.path.join(folder, f"photo_{i+1}.jpg")
            cv2.imwrite(path, final)
            captured.append(final)
            time.sleep(0.4)

        strip = make_strip(captured)
        cv2.imwrite(os.path.join(folder, "PRINT_STRIP_10x30.jpg"), strip)
        print("🖨 Strip siap cetak")

    if quit_pressed:
        break

cap.release()
cv2.destroyAllWindows()
