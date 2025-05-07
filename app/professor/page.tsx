"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import Link from "next/link"
import { CheckCircle, Clock, FileText, Users, MessageSquare, ChevronRight, BookOpen, X } from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"

// Sample data 
const pendingProjects = [
  {
    id: 1,
    title: "Smart Health Monitoring System",
    submittedBy: "Alice Johnson",
    submittedDate: "2025-05-10",
    course: "CS 180A",
    teamSize: 4,
  },
  {
    id: 2,
    title: "Campus Navigation App",
    submittedBy: "Bob Williams",
    submittedDate: "2025-05-08",
    course: "CS 180A",
    teamSize: 3,
  },
  {
    id: 3,
    title: "AI Study Assistant",
    submittedBy: "Charlie Brown",
    submittedDate: "2025-05-05",
    course: "INF 191A",
    teamSize: 5,
  },
]

const recentActivity = [
  {
    id: 1,
    type: "approval",
    project: "Virtual Lab Simulator",
    date: "2025-05-09",
    student: "Diana Miller",
  },
  {
    id: 2,
    type: "feedback",
    project: "Sustainable Energy Tracker",
    date: "2025-05-07",
    student: "Edward Davis",
  },
  {
    id: 3,
    type: "rejection",
    project: "Social Media Analytics Tool",
    date: "2025-05-06",
    student: "Fiona Wilson",
  },
  {
    id: 4,
    type: "approval",
    project: "Augmented Reality Campus Tour",
    date: "2025-05-04",
    student: "George Thompson",
  },
]

const currentCourses = [
  {
    id: 1,
    code: "CS 180A",
    name: "Senior Project",
    quarter: "Spring 2025",
    students: 24,
    projects: 8,
    pendingApprovals: 2,
  },
  {
    id: 2,
    code: "INF 191A",
    name: "Senior Project",
    quarter: "Spring 2025",
    students: 18,
    projects: 5,
    pendingApprovals: 1,
  },
  {
    id: 3,
    code: "CS 125",
    name: "Project in AI",
    quarter: "Spring 2025",
    students: 30,
    projects: 10,
    pendingApprovals: 0,
  },
]

const upcomingDeadlines = [
  {
    id: 1,
    title: "Project Proposal Approvals",
    course: "CS 180A",
    date: "2025-05-15",
    daysLeft: 5,
  },
  {
    id: 2,
    title: "Midterm Evaluations",
    course: "INF 191A",
    date: "2025-05-20",
    daysLeft: 10,
  },
  {
    id: 3,
    title: "Final Presentations",
    course: "CS 125",
    date: "2025-06-05",
    daysLeft: 26,
  },
]

export default function ProfessorDashboard() {
  const router = useRouter()
  const [loading, setLoading] = useState(true)
  const [activeTab, setActiveTab] = useState<"overview" | "projects" | "courses">("overview")

  useEffect(() => {
    // Check if user is logged in as professor
    const userRole = localStorage.getItem("userRole")
    const isLoggedIn = localStorage.getItem("isLoggedIn")

    if (!isLoggedIn || userRole !== "professor") {
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

      <div className="flex-1 bg-gray-50">
        {/* Professor Dashboard Tabs */}
        <div className="bg-white border-b mt-6">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 className="text-2xl font-bold pt-6 pb-4">Professor Dashboard</h1>
            <div className="flex overflow-x-auto">
              <button
                onClick={() => setActiveTab("overview")}
                className={`py-4 px-6 font-medium text-sm whitespace-nowrap ${
                  activeTab === "overview"
                    ? "border-b-2 border-[#4b84c7] text-[#4b84c7]"
                    : "text-gray-500 hover:text-gray-700"
                }`}
              >
                Overview
              </button>
              <button
                onClick={() => setActiveTab("projects")}
                className={`py-4 px-6 font-medium text-sm whitespace-nowrap ${
                  activeTab === "projects"
                    ? "border-b-2 border-[#4b84c7] text-[#4b84c7]"
                    : "text-gray-500 hover:text-gray-700"
                }`}
              >
                Student Projects
              </button>
              <button
                onClick={() => setActiveTab("courses")}
                className={`py-4 px-6 font-medium text-sm whitespace-nowrap ${
                  activeTab === "courses"
                    ? "border-b-2 border-[#4b84c7] text-[#4b84c7]"
                    : "text-gray-500 hover:text-gray-700"
                }`}
              >
                My Courses
              </button>
            </div>
          </div>
        </div>

        {/* Dashboard Content */}
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          {/* Overview Tab */}
          {activeTab === "overview" && (
            <div>
              {/* Welcome Card */}
              <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-2">Welcome, Dr. Smith</h2>
                <p className="text-gray-600 mb-4">
                  You have {pendingProjects.length} pending project approvals to review.
                </p>
                <div className="flex flex-wrap gap-4">
                  <Link
                    href="/professor/projects/pending"
                    className="inline-flex items-center px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
                  >
                    Review Pending Projects
                  </Link>
                </div>
              </div>

              {/* Dashboard Grid */}
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                {/* Pending Approvals Card */}
                <div className="bg-white rounded-lg shadow-md overflow-hidden">
                  <div className="bg-[#4b84c7] px-4 py-3 text-white">
                    <h3 className="font-medium flex items-center">
                      <Clock className="mr-2 h-5 w-5" /> Pending Approvals
                    </h3>
                  </div>
                  <div className="p-4">
                    {pendingProjects.length === 0 ? (
                      <p className="text-gray-500 italic">No pending approvals.</p>
                    ) : (
                      <div className="space-y-3">
                        {pendingProjects.map((project) => (
                          <div key={project.id} className="flex items-center justify-between">
                            <div>
                              <p className="font-medium">{project.title}</p>
                              <p className="text-sm text-gray-500">
                                {project.course} • {project.submittedBy} • {project.teamSize} members
                              </p>
                            </div>
                            <Link
                              href={`/professor/projects/${project.id}`}
                              className="p-1 text-blue-500 hover:bg-blue-50 rounded"
                            >
                              <ChevronRight size={18} />
                            </Link>
                          </div>
                        ))}
                      </div>
                    )}
                    {pendingProjects.length > 0 && (
                      <Link
                        href="/professor/projects/pending"
                        className="mt-4 text-sm text-[#4b84c7] hover:underline flex items-center"
                      >
                        View all pending approvals <ChevronRight className="h-4 w-4" />
                      </Link>
                    )}
                  </div>
                </div>

                {/* Recent Activity Card */}
                <div className="bg-white rounded-lg shadow-md overflow-hidden">
                  <div className="bg-[#4b84c7] px-4 py-3 text-white">
                    <h3 className="font-medium flex items-center">
                      <MessageSquare className="mr-2 h-5 w-5" /> Recent Activity
                    </h3>
                  </div>
                  <div className="p-4">
                    {recentActivity.length === 0 ? (
                      <p className="text-gray-500 italic">No recent activity.</p>
                    ) : (
                      <div className="space-y-3">
                        {recentActivity.map((activity) => (
                          <div key={activity.id} className="flex items-start">
                            <div
                              className={`mt-1 p-1 rounded-full mr-3 ${
                                activity.type === "approval"
                                  ? "bg-green-100 text-green-600"
                                  : activity.type === "rejection"
                                    ? "bg-red-100 text-red-600"
                                    : "bg-blue-100 text-blue-600"
                              }`}
                            >
                              {activity.type === "approval" ? (
                                <CheckCircle className="h-4 w-4" />
                              ) : activity.type === "rejection" ? (
                                <X className="h-4 w-4" />
                              ) : (
                                <MessageSquare className="h-4 w-4" />
                              )}
                            </div>
                            <div>
                              <p className="text-sm">
                                <span className="font-medium">
                                  {activity.type === "approval"
                                    ? "Approved"
                                    : activity.type === "rejection"
                                      ? "Rejected"
                                      : "Provided feedback on"}
                                </span>{" "}
                                {activity.project} by {activity.student}
                              </p>
                              <p className="text-xs text-gray-500">{activity.date}</p>
                            </div>
                          </div>
                        ))}
                      </div>
                    )}
                    <Link
                      href="/professor/activity"
                      className="mt-4 text-sm text-[#4b84c7] hover:underline flex items-center"
                    >
                      View all activity <ChevronRight className="h-4 w-4" />
                    </Link>
                  </div>
                </div>
              </div>

              {/* Current Courses */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div className="bg-[#4b84c7] px-6 py-4 text-white">
                  <h3 className="font-medium text-lg flex items-center">
                    <BookOpen className="mr-2 h-5 w-5" /> Current Courses
                  </h3>
                </div>
                <div className="p-6">
                  <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200">
                      <thead>
                        <tr>
                          <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Course
                          </th>
                          <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quarter
                          </th>
                          <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Students
                          </th>
                          <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Projects
                          </th>
                          <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pending
                          </th>
                          <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody className="bg-white divide-y divide-gray-200">
                        {currentCourses.map((course) => (
                          <tr key={course.id}>
                            <td className="px-4 py-4 whitespace-nowrap">
                              <div className="font-medium">{course.code}</div>
                              <div className="text-sm text-gray-500">{course.name}</div>
                            </td>
                            <td className="px-4 py-4 whitespace-nowrap text-sm">{course.quarter}</td>
                            <td className="px-4 py-4 whitespace-nowrap text-sm">{course.students}</td>
                            <td className="px-4 py-4 whitespace-nowrap text-sm">{course.projects}</td>
                            <td className="px-4 py-4 whitespace-nowrap">
                              {course.pendingApprovals > 0 ? (
                                <span className="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                  {course.pendingApprovals} pending
                                </span>
                              ) : (
                                <span className="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                  None
                                </span>
                              )}
                            </td>
                            <td className="px-4 py-4 whitespace-nowrap text-sm">
                              <Link href={`/professor/courses/${course.id}`} className="text-[#4b84c7] hover:underline">
                                View Details
                              </Link>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              {/* Quick Stats */}
              <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div className="bg-white rounded-lg shadow-md p-6 flex items-center">
                  <div className="bg-blue-100 p-3 rounded-full mr-4">
                    <Users className="h-6 w-6 text-blue-600" />
                  </div>
                  <div>
                    <p className="text-sm text-gray-500">Total Students</p>
                    <p className="text-2xl font-bold">72</p>
                  </div>
                </div>
                <div className="bg-white rounded-lg shadow-md p-6 flex items-center">
                  <div className="bg-green-100 p-3 rounded-full mr-4">
                    <FileText className="h-6 w-6 text-green-600" />
                  </div>
                  <div>
                    <p className="text-sm text-gray-500">Active Projects</p>
                    <p className="text-2xl font-bold">23</p>
                  </div>
                </div>
                <div className="bg-white rounded-lg shadow-md p-6 flex items-center">
                  <div className="bg-yellow-100 p-3 rounded-full mr-4">
                    <Clock className="h-6 w-6 text-yellow-600" />
                  </div>
                  <div>
                    <p className="text-sm text-gray-500">Pending Approvals</p>
                    <p className="text-2xl font-bold">{pendingProjects.length}</p>
                  </div>
                </div>
                <div className="bg-white rounded-lg shadow-md p-6 flex items-center">
                  <div className="bg-purple-100 p-3 rounded-full mr-4">
                    <CheckCircle className="h-6 w-6 text-purple-600" />
                  </div>
                  <div>
                    <p className="text-sm text-gray-500">Completed Projects</p>
                    <p className="text-2xl font-bold">18</p>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Projects Tab */}
          {activeTab === "projects" && (
            <div>
              <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-2">All Student Projects</h2>
                <p className="text-gray-600 mb-4">
                  This section allows you to view and manage all student projects across all your courses. You can
                  search, filter, and sort projects based on various criteria regardless of which course they belong to.
                </p>
                <div className="flex flex-wrap gap-4">
                  <button className="inline-flex items-center px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors">
                    View All Projects
                  </button>
                  <button className="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                    Pending Approvals
                  </button>
                </div>
              </div>

              <div className="bg-white rounded-lg shadow-md p-6">
                <p className="text-center text-gray-500 italic">
                  Project management interface would be displayed here, showing projects across all courses.
                </p>
              </div>
            </div>
          )}

          {/* Courses Tab */}
          {activeTab === "courses" && (
            <div>
              <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-2">My Courses</h2>
                <p className="text-gray-600 mb-4">
                  This section allows you to manage your courses, view student rosters, and access course-specific
                  projects. You can organize and manage projects by course, view team formations, and handle course
                  administration tasks.
                </p>
                <div className="flex flex-wrap gap-4">
                  <button className="inline-flex items-center px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors">
                    View All Courses
                  </button>
                  <button className="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                    Current Quarter
                  </button>
                </div>
              </div>

              <div className="bg-white rounded-lg shadow-md p-6">
                <p className="text-center text-gray-500 italic">
                  Course management interface would be displayed here, showing courses and their associated projects.
                </p>
              </div>
            </div>
          )}
        </div>
      </div>
    </main>
  )
}
