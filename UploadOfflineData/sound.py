import numpy as np
import matplotlib.pyplot as plt
import soundfile as sf
from scipy.fftpack import fft
from IPython.display import display
import ipywidgets as widgets

# Function to plot the spectralizer
def plot_spectralizer(data, sample_rate):
    # If stereo, take one channel
    if len(data.shape) > 1:
        data = data[:, 0]

    # Perform Fourier Transform
    n = len(data)
    T = 1.0 / sample_rate
    yf = fft(data)
    xf = np.linspace(0.0, 1.0 / (2.0 * T), n // 2)

    # Plot the frequency spectrum
    plt.figure(figsize=(10, 6))
    plt.plot(xf, 2.0/n * np.abs(yf[:n//2]))
    plt.title("Audio Spectralizer")
    plt.xlabel("Frequency (Hz)")
    plt.ylabel("Amplitude")
    plt.grid()
    plt.show()

# Function to handle file upload
def on_file_upload(change):
    file_content = list(change['new'].values())[0]['content']
    with open("uploaded_file.wav", "wb") as f:
        f.write(file_content)
    data, sample_rate = sf.read("uploaded_file.wav")
    plot_spectralizer(data, sample_rate)

# File upload widget
upload = widgets.FileUpload(accept='.wav', multiple=False)
upload.observe(on_file_upload, names='value')

display(upload)
