<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sidebar</title>
    <style>
        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            padding: 10px;
            border-right: 1px solid #34495e;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar h4 {
            color: #ecf0f1;
            padding: 10px 0;
        }
        .nav-item {
            padding: 5px 0;
        }
        .nav-link {
            color: #bdc3c7;
            display: block;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-link:hover {
            background-color: #1abc9c;
            color: white;
        }
        .dropdown-menu {
            display: none;
            list-style-type: none;
            padding-left: 15px;
            margin-top: 5px;
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
                height: auto;
            }
            .menu-toggle {
                display: block;
                background-color: #1abc9c;
                color: white;
                padding: 10px;
                text-align: center;
                cursor: pointer;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="#">Total Students</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Total Students List</a></li>
            <li class="nav-item"><a class="nav-link" href="#">User Search</a></li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleMenu('nationality-menu')">Students by Nationality</a>
                <ul class="dropdown-menu" id="nationality-menu">
                    <li><a class="nav-link" href="#">Bangladesh</a></li>
                    <li><a class="nav-link" href="#">India</a></li>
                    <li><a class="nav-link" href="#">Nepal</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleMenu('religion-menu')">Students by Religion</a>
                <ul class="dropdown-menu" id="religion-menu">
                    <li><a class="nav-link" href="#">Muslim</a></li>
                    <li><a class="nav-link" href="#">Hindu</a></li>
                    <li><a class="nav-link" href="#">Boddho</a></li>
                    <li><a class="nav-link" href="#">Christian</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleMenu('department-menu')">Students by Department</a>
                <ul class="dropdown-menu" id="department-menu">
                    <li><a class="nav-link" href="#">Computer Science</a></li>
                    <li><a class="nav-link" href="#">Mechanical</a></li>
                    <li><a class="nav-link" href="#">Forestry</a></li>
                    <li><a class="nav-link" href="#">Tourism</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleMenu('course-menu')">Students by Course</a>
                <ul class="dropdown-menu" id="course-menu">
                    <li><a class="nav-link" href="#">BSC</a></li>
                    <li><a class="nav-link" href="#">MSC</a></li>
                    <li><a class="nav-link" href="#">PHD</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleMenu('upload-menu')">Upload Photo and Video</a>
                <ul class="dropdown-menu" id="upload-menu">
                    <li><a class="nav-link" href="#">Upload Photo & Video</a></li>
                    <li><a class="nav-link" href="#">View Uploaded Media</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Category</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Upload Slider</a></li>
        </ul>
    </div>

    <script>
        function toggleMenu(menuId) {
            var menu = document.getElementById(menuId);
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }
    </script>
</body>
</html>
