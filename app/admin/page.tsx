"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import Link from "next/link"
import { Trophy, Users, FileText, ImageIcon } from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"

export default function AdminDashboard() {
  const router = useRouter()
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    // Check if user is logged in as admin
    const userRole = localStorage.getItem("userRole")
    const isLoggedIn = localStorage.getItem("isLoggedIn")

    if (!isLoggedIn || userRole !== "admin") {
      router.push("/login")
      return
    }

    setLoading(false)
  }, [router])

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p>Loading...</p>
      </div>
    )
  }

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      <div className="flex-1 p-6 bg-gray-50">
        <div className="max-w-7xl mx-auto">
          <h1 className="text-3xl font-bold mb-6">Admin Dashboard</h1>

          <div className="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 className="text-xl font-semibold mb-4">Welcome to the Admin View</h2>
            <p className="text-gray-600 mb-4">
              This is the admin dashboard for the UCI ICS Capstone Project Archive. Here you can manage all aspects of
              the system.
            </p>
          </div>

          {/* Admin Actions Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {/* Featured Projects & Awards Card */}
            <Link
              href="/admin/featured-projects"
              className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
            >
              <div className="flex items-start">
                <div className="bg-yellow-100 p-3 rounded-full mr-4">
                  <Trophy className="h-6 w-6 text-yellow-600" />
                </div>
                <div>
                  <h3 className="text-lg font-semibold mb-2">Featured Projects & Awards</h3>
                  <p className="text-gray-600">Manage featured projects and award winners.</p>
                </div>
              </div>
            </Link>

            {/* Manage Projects Card */}
            <div className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow cursor-pointer">
              <div className="flex items-start">
                <div className="bg-blue-100 p-3 rounded-full mr-4">
                  <FileText className="h-6 w-6 text-blue-600" />
                </div>
                <div>
                  <h3 className="text-lg font-semibold mb-2">Manage Projects</h3>
                  <p className="text-gray-600">Add, edit, or remove projects from the archive.</p>
                </div>
              </div>
            </div>

            {/* User Management Card */}
            <div className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow cursor-pointer">
              <div className="flex items-start">
                <div className="bg-green-100 p-3 rounded-full mr-4">
                  <Users className="h-6 w-6 text-green-600" />
                </div>
                <div>
                  <h3 className="text-lg font-semibold mb-2">User Management</h3>
                  <p className="text-gray-600">Manage professors, students, and admin accounts.</p>
                </div>
              </div>
            </div>

            {/* Photo Gallery Management Card */}
            <Link href="/admin/gallery" className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
              <div className="flex items-start">
                <div className="bg-purple-100 p-3 rounded-full mr-4">
                  <ImageIcon className="h-6 w-6 text-purple-600" />
                </div>
                <div>
                  <h3 className="text-lg font-semibold mb-2">Photo Gallery</h3>
                  <p className="text-gray-600">Manage photos displayed in the homepage gallery.</p>
                </div>
              </div>
            </Link>
          </div>

          {/* System Information */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="bg-white rounded-lg shadow-md p-6">
              <h3 className="text-lg font-semibold mb-3">System Statistics</h3>
              <div className="space-y-2">
                <div className="flex justify-between">
                  <span className="text-gray-600">Total Projects:</span>
                  <span className="font-medium">124</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Active Professors:</span>
                  <span className="font-medium">18</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Pending Approvals:</span>
                  <span className="font-medium">3</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Featured Projects:</span>
                  <span className="font-medium">5</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Award Winners:</span>
                  <span className="font-medium">12</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Gallery Photos:</span>
                  <span className="font-medium">24</span>
                </div>
              </div>
            </div>
            <div className="bg-white rounded-lg shadow-md p-6">
              <h3 className="text-lg font-semibold mb-3">Recent Activity</h3>
              <div className="space-y-3">
                <div className="border-l-4 border-purple-500 pl-3 py-1">
                  <p className="text-sm text-gray-600">Today at 11:45 AM</p>
                  <p className="font-medium">New gallery photo added: "Spring 2023 Expo"</p>
                </div>
                <div className="border-l-4 border-blue-500 pl-3 py-1">
                  <p className="text-sm text-gray-600">Today at 10:23 AM</p>
                  <p className="font-medium">New project submitted: "AI-Powered Health Monitoring"</p>
                </div>
                <div className="border-l-4 border-green-500 pl-3 py-1">
                  <p className="text-sm text-gray-600">Yesterday at 2:45 PM</p>
                  <p className="font-medium">Project approved: "Blockchain for Supply Chain"</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  )
}
