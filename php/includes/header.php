<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCI ICS Capstone Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#4b84c7",
                        secondary: "#f8e858",
                        content: "#848484",
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen flex flex-col">
    <header class="bg-[#4b84c7] text-white py-6 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <!-- Main title -->
                <a href="index.php" class="text-2xl font-bold hover:text-gray-200 transition-colors">
                    UCI ICS Capstone Project
                </a>

                <!-- Staff Login link -->
                <a href="index.php?page=login" class="text-sm text-white hover:text-gray-200 transition-colors">
                    Staff Login
                </a>
            </div>
        </div>
    </header>
