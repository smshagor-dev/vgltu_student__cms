<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .page-break {
            page-break-before: always; /* Forces content to start on a new page */
        }

        img {
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div style="width: 100%; text-align: center; margin-bottom: 20px;">
        <h1>Details of {{ $user->full_name }}</h1>
        <h2>Room Number: {{ $user->room_number }}</h2>
    </div>

    <!-- Centered User Photo -->
    <div style="width: 100%; text-align: center; margin-bottom: 30px;">
        <img src="var:user_photo" alt="User Photo" style="width: 200px; height: 200px; border-radius: 50%;">
    </div>

    <!-- User Details -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div style="width: 60%; padding-right: 20px;">
            <p><strong>Name:</strong> {{ $user->full_name }}</p>
            <p><strong>Mobile Number:</strong> {{ $user->mobile_number }}</p>
            <p><strong>Room Number:</strong> {{ $user->room_number }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Country:</strong> {{ $user->country }}</p>
            <p><strong>Address:</strong> {{ $user->address }}</p>
            <p><strong>Gender:</strong> {{ $user->gender }}</p>
            <p><strong>Religion:</strong> {{ $user->religion }}</p>
            <p><strong>Date of Birth:</strong> {{ $user->date_of_birth }}</p>
            <p><strong>Course Type:</strong> {{ $user->course_type }}</p>
            <p><strong>Department:</strong> {{ $user->department }}</p>
            @isset($user->course_year)
                <p><strong>Course Year:</strong> {{ $user->course_year }}</p>
            @endisset
            
            @isset($user->course_language)
                <p><strong>Course Language:</strong> {{ $user->course_language }}</p>
            @endisset
        </div>
    </div>

    <!-- Page Break before Additional Photos -->
    <div class="page-break"></div>

    <!-- Additional Photos (Passport, Visa, Green Card) -->
    <div style="display: flex; justify-content: space-around; margin-top: 40px; text-align: center;">
        <div style="width: 100%;">
            <h3>Passport Photo</h3>
            <img src="var:passport_photo" alt="Passport Photo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        
        <div class="page-break"></div>
        
        <div style="width: 100%;">
            <h3>Visa Photo</h3>
            <img src="var:visa_photo" alt="Visa Photo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        
        <div class="page-break"></div>
        
        <div style="width: 100%;">
            <h3>Green Card Photo</h3>
            <img src="var:green_card_photo" alt="Green Card Photo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
    </div>

</body>
</html>
