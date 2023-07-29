<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom Business Chatbot</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #25d366;
      color: black;
      text-align: center;
      padding: 20px;
    }

    .grid-container {
      display: grid;
      grid-template-columns: 3fr 1fr;
      grid-gap: 20px;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }

    label {
      display: block;
      margin-bottom: 10px;
    }

    input[type="text"] {
      width: 70%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-bottom: 15px;
    }

    .btn-upload {
      display: inline-block;
      background-color: #25d366;
      /* WhatsApp Green color */
      color: #fff;
      padding: 10px 20px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 16px;
      border: none;
    }

    .input-upload {
      display: inline-block;
      background-color: black;
      /* WhatsApp Green color */
      color: #fff;
      padding: 10px 20px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 16px;
      border: none;
    }

    .btn-upload:hover {
      background-color: black;
      color:white
    }

    .subheading {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .steps {
      margin-bottom: 20px;
    }

    #whatsapp-image {

      height: 400px;
      display: block;
      margin: 0 auto;
    }

    h4 {
      color: white;
    }

    .footer-container{
      position: fixed;
      bottom: 0;
      margin-bottom: 35px;
    }
  </style>
</head>

<body>
  <header>
    <h1>Build Your Custom Business Chatbot</h1>
    <h3>Follow below steps to build your chatbot</h3>
    <h4>
      <?php echo "(Current Whatsapp number: " . get_admin_phone_number() . ")" ?>
    </h4>

  </header>
  <div class="grid-container">
    <div class="grid-item">
      

      <form action="" method="POST">
        <label for="whatsapp-number">Enter your WhatsApp number:</label>
        <input type="text" id="whatsapp-number" placeholder="e.g., 9923456789" name="phone_number">

        <input type="submit" target="blank" name="submit" class="input-upload" />

      </form>
      <div class="steps">
        <p class="subheading">Follow these steps:</p>
        <ol>
          <li><b>Step 1:</b> Submit your WhatsApp number in the upper field.</li>
          <li><b>Step 2:</b> After submitting your number, click on the connect to WhatsApp button.</li>
          <li><b>Step 3:</b> Just upload your document in WhatsApp and enjoy the chatbot service.</li>
        </ol>
      </div>
      <a type="submit" target="blank" href="https://wa.link/4x7txe" class="btn-upload">Connect to WhatsApp</a>


    </div>
    <div class="grid-item">
      <img src="https://i.postimg.cc/1RjJNZzc/phn.png" alt="WhatsApp Logo" id="whatsapp-image">
    </div>
  </div>

<footer class="footer-container">
<p> Author: <a target="blank" href="https://whatsgpt.tech/" >WhatsGPT Technology by Cresite</a></p>
</footer>


  <script src="wp-content/plugins/Mychatbot/js/script.js"
    data-admin-phone="<?php echo get_admin_phone_number(); ?>"></script>


</body>

</html>

<?php

if (isset($_POST["submit"])) {
  $phone_number = $_POST["phone_number"];

  if (strlen($phone_number) === 10) {
    $sanitized_phone_number = sanitize_text_field($phone_number);
    $result = update_option('admin_phone_number', $sanitized_phone_number);

    if ($result) {
      echo "Phone number is updated succesfully";

    } else {
      echo "Error while submitting phone number, please try again later";

      exit();

    }

  } else {
    echo "Please provide the correct number";
  }



}

function get_admin_phone_number()
{
  // Retrieve the stored admin phone number from the database.
  $admin_phone_number = get_option('admin_phone_number', ''); // Provide a default value if the option is not set.

  // Return the admin phone number.
  return $admin_phone_number;
}

$num = get_admin_phone_number();



?>