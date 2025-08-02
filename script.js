// -----------------------------
// Live Jewelry Try-On Feature
// -----------------------------
const video = document.getElementById("video");
const canvas = document.getElementById("overlay");
if (video && canvas) {
  const context = canvas.getContext("2d");

  Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models")
  ]).then(startVideo);

  function startVideo() {
    navigator.mediaDevices
      .getUserMedia({ video: true })
      .then((stream) => {
        video.srcObject = stream;
      })
      .catch((err) => console.error("Camera error:", err));
  }

  video.addEventListener("play", () => {
    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    const loop = async () => {
      const detection = await faceapi
        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks();

      context.clearRect(0, 0, canvas.width, canvas.height);

      if (detection && detection.landmarks) {
        const landmarks = detection.landmarks;
        const leftEar = landmarks.getLeftEye()[0];
        const rightEar = landmarks.getRightEye()[3];

        const earring = new Image();
        earring.src = "images/earring.png";

        earring.onload = () => {
          context.drawImage(earring, leftEar.x - 15, leftEar.y - 10, 30, 30);
          context.drawImage(earring, rightEar.x - 15, rightEar.y - 10, 30, 30);
        };
      }

      requestAnimationFrame(loop);
    };

    loop();
  });
}

// -----------------------------
// Chat Toggle Button
// -----------------------------
function toggleChat() {
  const chatWindow = document.getElementById("chat-window");
  if (chatWindow) {
    chatWindow.style.display =
      chatWindow.style.display === "none" ? "block" : "none";
  }
}

// -----------------------------
// Protect Links That Need Login
// -----------------------------
function checkLogin(event) {
  event.preventDefault();
  const username = localStorage.getItem("username");

  if (!username) {
    alert("Please login or signup first!");
    window.location.href = "login.php";
  } else {
    // Allow the link to navigate
    window.location.href = event.currentTarget.href;
  }
}

document.querySelectorAll(".protected-link").forEach((link) => {
  link.addEventListener("click", checkLogin);
});
