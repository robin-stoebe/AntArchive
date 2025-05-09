<?php
// Handle form submission and redirects before any output
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role'])) {
        $role = $_POST['role'];
        
        // Redirect based on role
        if ($role === 'admin') {
            header('Location: index.php?page=admin');
            exit;
        } elseif ($role === 'professor') {
            header('Location: index.php?page=professor');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - UCI ICS Capstone</title>
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
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Back to public view button -->
    <div class="absolute top-4 left-4">
        <a href="index.php" class="flex items-center px-4 py-2 bg-white rounded-md shadow-sm text-[#4b84c7] hover:bg-gray-100 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Return to public view
        </a>
    </div>

    <!-- UCI Logo -->
    <div class="flex justify-center pt-16 pb-8">
        <div class="text-center">
            <div class="flex items-center justify-center">
                <span class="text-[#4b84c7] text-6xl font-bold">UCI</span>
                <div class="ml-4 text-left">
                    <div class="text-[#4b84c7] text-lg font-medium">University of</div>
                    <div class="text-[#4b84c7] text-lg font-medium">California, Irvine</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Selection Card -->
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-md shadow-lg p-8">
            <h2 class="text-2xl font-medium text-center text-gray-800 mb-6">Staff Login</h2>
            <p class="text-center text-gray-600 mb-8">Select your role to continue</p>

            <form method="POST" action="index.php?page=login" class="space-y-4">
                <button 
                    type="submit" 
                    name="role" 
                    value="professor" 
                    class="w-full py-3 px-4 bg-[#4b84c7] text-white font-medium rounded-md hover:bg-[#3b6ba0] transition-colors flex items-center justify-center"
                >
                    Login as Professor
                </button>

                <button 
                    type="submit" 
                    name="role" 
                    value="admin" 
                    class="w-full py-3 px-4 bg-[#FFD200] text-[#4b84c7] font-medium rounded-md hover:bg-[#FFDA33] transition-colors flex items-center justify-center"
                >
                    Login as Admin
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                This is a demonstration version with simplified login.
                <br>
                No credentials required.
            </div>
        </div>
    </div>
</body>
</html>
