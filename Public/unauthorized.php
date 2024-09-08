<!DOCTYPE html>
<html>

<head>
    <title>Unauthorized Access</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background-color: #f5f5f5;

    }

    .container {
        width: 600px;
        height: 300px;
        background-color: #fff;
        padding: 40px;
        border-radius: 4px;
        box-shadow: 0px 0 30px rgba(255, 43, 43, 0.8);
        text-align: center;
    }

    h1 {
        margin-top: 0;
        color: #ff4d4f;
        font-size: 40px;
    }

    button {
        background-color: #fff;
        padding: 10px;
        margin: 10px;
        border: 2px solid #ff4d4f;
        border-radius: 4px;
        font-weight: bolder;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-size: 20px;

    }

    button:hover {
        background-color: #ff4d4f;
        color: white;
        font-weight: bolder;

        /* padding: 10px;
        margin: 10px;
        border: 2px solid #ff4d4f;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-size: 20px; */
    }

    p {
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: bolder;

    }

    .warning-icon {
        font-size: 100px;
        color: #ff4d4f;
    }
    </style>
</head>

<body>
    <div class="container">
        <span class="warning-icon">&#9888;</span>
        <h1>Unauthorized Access</h1>
        <p>Sorry, you do not have permission to access this page.</p>
        <a href="index.php"><button>Back to Home </button></a>
    </div>
</body>

</html>