import cv2
import tkinter as tk
from PIL import Image, ImageTk
import datetime
import os

# Folder simpan foto
SAVE_DIR = "photos"
os.makedirs(SAVE_DIR, exist_ok=True)

# Init camera
cap = cv2.VideoCapture(0)

# Tkinter window
window = tk.Tk()
window.title("Python PhotoBox")
window.geometry("800x600")

label = tk.Label(window)
label.pack()

def take_photo():
    ret, frame = cap.read()
    if ret:
        filename = datetime.datetime.now().strftime("%Y%m%d_%H%M%S") + ".jpg"
        filepath = os.path.join(SAVE_DIR, filename)
        cv2.imwrite(filepath, frame)
        print(f"Foto tersimpan: {filepath}")

btn = tk.Button(window, text="📸 Ambil Foto", command=take_photo, font=("Arial", 16))
btn.pack(pady=10)

def update_frame():
    ret, frame = cap.read()
    if ret:
        frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        img = Image.fromarray(frame)
        imgtk = ImageTk.PhotoImage(image=img)
        label.imgtk = imgtk
        label.configure(image=imgtk)
    window.after(10, update_frame)

update_frame()
window.mainloop()

cap.release()
cv2.destroyAllWindows()
