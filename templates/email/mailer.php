<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <meta charset="UTF-8">
    <title>Sending Email</title>
</head>

<body>
    <div class="container">
        <h1>Sending an email using Gmail SMTP in PHP</h1>
        <div class="jumbotron">
            <hr />
            <form action="" method="post" enctype="multipart/form-data">
                <label>To:</label><br>
                <input type="email" placeholder="To: Email Id" name="toid" required /><br>

                <label>Subject:</label><br>
                <input type="text" placeholder="Subject" name="subject" required /><br>

                <label>Message:</label><br>
                <textarea rows="4" placeholder="Enter Your Message..." name="message" required></textarea><br>

                <label>Choose a File/Image</label>
                <input type="file" class="file" name="image" id="image" />

                <input type="submit" value="Send" name="send" /><br>
            </form>
        </div>
    </div>
</body>

</html>