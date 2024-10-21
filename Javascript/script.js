// Event Listeners
document
  .getElementById("infocheckbox")
  .addEventListener("change", infoClick);
document
  .getElementById("SubmitButton")
  .addEventListener("click", submitButton);

let receiveInfoText = "true";

function infoClick() {
  receiveInfoText = document.getElementById("infocheckbox").checked
    ? "true"
    : "false";
}

function submitButton(event) {
  event.preventDefault(); // Prevent form submission

  let firstNameText = document.getElementById("firstName").value.trim();
  let lastNameText = document.getElementById("lastName").value.trim();
  let companyText = document.getElementById("company").value.trim();
  let jobTitle = document.getElementById("jobTitle").value.trim();
  let emailText = document.getElementById("email").value.trim();
  let industryText = document.getElementById("Field").value;
  let notification = document.getElementById("notification");
  let regdate = new Date().getTime();

  // Input validation
  if (
    !firstNameText ||
    !lastNameText ||
    !emailText ||
    !companyText ||
    !jobTitle
  ) {
    notification.innerText = "Please complete all fields";
    return;
  }
  if (!validateEmail(emailText)) {
    notification.innerText = "Please enter a valid email address";
    return;
  }

  let qrString = [
    firstNameText,
    lastNameText,
    companyText,
    jobTitle,
    industryText,
    emailText,
    receiveInfoText,
    regdate.toString(),
  ].join("~");

  // Generate QR Code
  let qrCodeContainer = document.createElement("div");
  new QRCode(qrCodeContainer, {
    text: qrString,
    width: 400,
    height: 400,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.L,
  });

  // Wait for QR code to render
  setTimeout(() => {
    let qrImage = qrCodeContainer.querySelector("img").src;

    // console.log(qrCodeContainer);

    // Send email with QR code
    sendEmail(emailText, qrImage, firstNameText);

    notification.innerText = "Email sent successfully!";
  }, 500);
}

function validateEmail(email) {
  const re =
    /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
  return re.test(String(email).toLowerCase());
}

function sendEmail(email, qrImage, name) {

    emailjs
    .send("service_qs0f8mi", "template_teeyw7r", {
      to_email: email,
      to_name: name,
      qr_code: qrImage
    })
    .then(
      (response) => {
        console.log("SUCCESS!", response.status, response.text);
         // Redirect to the specified URL after a short delay
         setTimeout(() => {
            window.location.href = 'https://www.jetaviation.com/';
          }, 2000); // Redirect after 2 seconds (2000 milliseconds)
      },
      (err) => {
        console.error("FAILED...", err);
        document.getElementById("notification").innerText =
          "Failed to send email.";
      }
    );
}
