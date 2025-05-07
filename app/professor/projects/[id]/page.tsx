"use client"

import type React from "react"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import Link from "next/link"
import {
  ArrowLeft,
  Calendar,
  Users,
  Tag,
  BookOpen,
  GraduationCap,
  CheckCircle,
  X,
  AlertTriangle,
  Download,
  ExternalLink,
} from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"

// Sample project data
const projectData = {
  id: "1",
  title: "Smart Health Monitoring System",
  description: "A comprehensive health monitoring system for elderly patients using IoT devices and AI.",
  longDescription: `This capstone project aims to develop a comprehensive health monitoring system for elderly patients, leveraging IoT devices and artificial intelligence to provide real-time health tracking and emergency alerts.

The system will include wearable sensors that monitor vital signs such as heart rate, blood pressure, and body temperature. These sensors will connect to a central hub in the patient's home, which processes the data and sends it to a cloud-based platform for analysis.

Machine learning algorithms will analyze the data to detect anomalies and predict potential health issues before they become critical. The system will also include a mobile application for caregivers and family members, allowing them to monitor the patient's health status remotely and receive alerts in case of emergencies.

The project addresses the growing need for remote healthcare solutions, especially for elderly patients who prefer to age in place. By providing continuous monitoring and early detection of health issues, the system aims to improve patient outcomes, reduce hospitalizations, and provide peace of mind for caregivers.`,
  status: "pending", // pending, approved, rejected
  submittedDate: "2025-05-10",
  quarter: "Spring",
  year: "2025",
  degree: "BS",
  course: "CS 180A",
  professor: "Dr. Smith",
  teamMembers: [
    { name: "Alice Johnson", role: "Team Lead", email: "alice@uci.edu" },
    { name: "Bob Williams", role: "Backend Developer", email: "bob@uci.edu" },
    { name: "Charlie Brown", role: "Frontend Developer", email: "charlie@uci.edu" },
    { name: "Diana Miller", role: "Data Scientist", email: "diana@uci.edu" },
  ],
  tags: ["Healthcare", "IoT", "Artificial Intelligence", "Mobile Apps"],
  links: ["https://project-website.com", "https://github.com/project-repo"],
  files: [
    {
      name: "Project Proposal.pdf",
      type: "PDF",
      size: "1.2 MB",
      url: "#",
    },
    {
      name: "System Architecture.png",
      type: "Image",
      size: "0.8 MB",
      url: "#",
    },
    {
      name: "Preliminary Results.xlsx",
      type: "Excel",
      size: "0.5 MB",
      url: "#",
    },
  ],
  feedback: [
    {
      id: 1,
      author: "Dr. Smith",
      date: "2025-05-08",
      message:
        "The project proposal looks promising. Please provide more details about the machine learning algorithms you plan to use for anomaly detection.",
    },
    {
      id: 2,
      author: "Alice Johnson",
      date: "2025-05-09",
      message:
        "Thank you for the feedback. We plan to use a combination of supervised and unsupervised learning techniques, including isolation forests and autoencoders for anomaly detection.",
    },
  ],
}

export default function ProjectReviewPage() {
  const params = useParams()
  const router = useRouter()
  const [loading, setLoading] = useState(true)
  const [project, setProject] = useState<typeof projectData | null>(null)
  const [feedbackText, setFeedbackText] = useState("")
  const [approvalStatus, setApprovalStatus] = useState<"pending" | "approved" | "rejected">("pending")

  useEffect(() => {
    // Check if user is logged in as professor
    const userRole = localStorage.getItem("userRole")
    const isLoggedIn = localStorage.getItem("isLoggedIn")

    if (!isLoggedIn || userRole !== "professor") {
      router.push("/login")
      return
    }

    // In a real app, fetch the project data based on the ID
    setProject(projectData)
    setApprovalStatus(projectData.status)
    setLoading(false)
  }, [params.id, router])

  const handleSubmitFeedback = (e: React.FormEvent) => {
    e.preventDefault()
    if (!feedbackText.trim()) return

    // In a real app, this would send the feedback to an API
    const newFeedback = {
      id: (project?.feedback?.length || 0) + 1,
      author: "Dr. Smith",
      date: new Date().toISOString().split("T")[0],
      message: feedbackText,
    }

    setProject((prev) => {
      if (!prev) return null
      return {
        ...prev,
        feedback: [...(prev.feedback || []), newFeedback],
      }
    })

    setFeedbackText("")
  }

  const handleApprove = () => {
    // In a real app, this would send an API request to approve the project
    setApprovalStatus("approved")
    setProject((prev) => {
      if (!prev) return null
      return {
        ...prev,
        status: "approved",
      }
    })
  }

  const handleReject = () => {
    // In a real app, this would send an API request to reject the project
    setApprovalStatus("rejected")
    setProject((prev) => {
      if (!prev) return null
      return {
        ...prev,
        status: "rejected",
      }
    })
  }

  if (loading) {
    return (
      <main className="min-h-screen flex flex-col">
        <Header />
        <Navigation />
        <div className="flex-1 flex items-center justify-center">
          <p>Loading project details...</p>
        </div>
      </main>
    )
  }

  if (!project) {
    return (
      <main className="min-h-screen flex flex-col">
        <Header />
        <Navigation />
        <div className="flex-1 flex flex-col items-center justify-center">
          <h2 className="text-2xl font-bold mb-4">Project Not Found</h2>
          <p className="mb-6">The project you're looking for doesn't exist or has been removed.</p>
          <Link
            href="/professor"
            className="flex items-center gap-2 bg-[#4b84c7] text-white py-2 px-4 rounded hover:bg-[#3b6ba0]"
          >
            <ArrowLeft size={16} /> Back to Dashboard
          </Link>
        </div>
      </main>
    )
  }

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      <div className="flex-1 bg-gray-50">
        {/* Back button */}
        <div className="bg-white border-b">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <button onClick={() => router.back()} className="flex items-center text-[#4b84c7] hover:underline">
              <ArrowLeft size={16} className="mr-1" /> Back to Projects
            </button>
          </div>
        </div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div className="bg-white rounded-lg shadow-md overflow-hidden">
            {/* Project header */}
            <div className="bg-[#4b84c7] text-white p-6">
              <div className="flex flex-wrap justify-between items-start">
                <div>
                  <h1 className="text-3xl font-bold mb-2">{project.title}</h1>
                  <p className="text-lg opacity-90 mb-4">{project.description}</p>
                  <div className="flex flex-wrap gap-2 items-center text-sm">
                    <span className="flex items-center">
                      <Calendar size={16} className="mr-1" /> {project.quarter} {project.year}
                    </span>
                    <span className="flex items-center">
                      <BookOpen size={16} className="mr-1" /> {project.course}
                    </span>
                    <span className="flex items-center">
                      <GraduationCap size={16} className="mr-1" /> {project.degree}
                    </span>
                    <span className="flex items-center">
                      <Users size={16} className="mr-1" /> {project.teamMembers.length} Team Members
                    </span>
                  </div>
                </div>
                <div className="mt-2 sm:mt-0">
                  <div
                    className={`px-4 py-2 rounded-full font-bold flex items-center ${
                      approvalStatus === "approved"
                        ? "bg-green-100 text-green-800"
                        : approvalStatus === "rejected"
                          ? "bg-red-100 text-red-800"
                          : "bg-yellow-100 text-yellow-800"
                    }`}
                  >
                    {approvalStatus === "approved" ? (
                      <CheckCircle size={16} className="mr-1" />
                    ) : approvalStatus === "rejected" ? (
                      <X size={16} className="mr-1" />
                    ) : (
                      <AlertTriangle size={16} className="mr-1" />
                    )}
                    {approvalStatus === "approved"
                      ? "Approved"
                      : approvalStatus === "rejected"
                        ? "Rejected"
                        : "Pending Review"}
                  </div>
                </div>
              </div>
            </div>

            {/* Project content */}
            <div className="p-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {/* Main content - 2/3 width on desktop */}
                <div className="md:col-span-2">
                  {/* Project image */}
                  <div className="bg-[#d9d9d9] aspect-video mb-6 rounded-md"></div>

                  {/* Project description */}
                  <section className="mb-8">
                    <h2 className="text-xl font-bold mb-4">Project Description</h2>
                    <div className="prose max-w-none">
                      {project.longDescription.split("\n\n").map((paragraph, index) => (
                        <p key={index} className="mb-4">
                          {paragraph}
                        </p>
                      ))}
                    </div>
                  </section>

                  {/* Project files */}
                  <section className="mb-8">
                    <h2 className="text-xl font-bold mb-4">Project Files</h2>
                    <div className="space-y-3">
                      {project.files.map((file, index) => (
                        <div
                          key={index}
                          className="flex items-center justify-between p-3 border rounded-md hover:bg-gray-50"
                        >
                          <div>
                            <p className="font-medium">{file.name}</p>
                            <p className="text-sm text-gray-500">
                              {file.type} • {file.size}
                            </p>
                          </div>
                          <a href={file.url} className="text-[#4b84c7] hover:text-[#3b6ba0] flex items-center" download>
                            <Download size={18} />
                          </a>
                        </div>
                      ))}
                    </div>
                  </section>

                  {/* Project links */}
                  {project.links.length > 0 && (
                    <section className="mb-8">
                      <h2 className="text-xl font-bold mb-4">Project Links</h2>
                      <div className="space-y-2">
                        {project.links.map((link, index) => (
                          <a
                            key={index}
                            href={link}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex items-center text-[#4b84c7] hover:underline"
                          >
                            <ExternalLink size={16} className="mr-2" /> {link}
                          </a>
                        ))}
                      </div>
                    </section>
                  )}

                  {/* Feedback and Discussion */}
                  <section className="mb-8">
                    <h2 className="text-xl font-bold mb-4">Feedback & Discussion</h2>
                    <div className="space-y-4 mb-6">
                      {project.feedback.map((item) => (
                        <div key={item.id} className="p-4 border rounded-md">
                          <div className="flex justify-between items-center mb-2">
                            <span className="font-medium">{item.author}</span>
                            <span className="text-sm text-gray-500">{item.date}</span>
                          </div>
                          <p className="text-gray-700">{item.message}</p>
                        </div>
                      ))}
                    </div>

                    <form onSubmit={handleSubmitFeedback}>
                      <label htmlFor="feedback" className="block font-medium mb-2">
                        Add Feedback
                      </label>
                      <textarea
                        id="feedback"
                        rows={4}
                        className="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                        placeholder="Enter your feedback here..."
                        value={feedbackText}
                        onChange={(e) => setFeedbackText(e.target.value)}
                      ></textarea>
                      <button
                        type="submit"
                        className="mt-2 px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
                      >
                        Submit Feedback
                      </button>
                    </form>
                  </section>
                </div>

                <div>
                  {/* Approval Actions */}
                  <section className="mb-6 p-4 border rounded-md">
                    <h2 className="text-lg font-bold mb-3">Project Approval</h2>
                    <p className="text-sm text-gray-600 mb-4">
                      Review the project details and provide your decision below.
                    </p>
                    <div className="space-y-2">
                      <button
                        onClick={handleApprove}
                        disabled={approvalStatus === "approved"}
                        className={`w-full py-2 px-4 rounded-md flex items-center justify-center ${
                          approvalStatus === "approved"
                            ? "bg-green-100 text-green-800 cursor-not-allowed"
                            : "bg-green-600 text-white hover:bg-green-700"
                        }`}
                      >
                        <CheckCircle size={16} className="mr-2" />
                        {approvalStatus === "approved" ? "Approved" : "Approve Project"}
                      </button>
                      <button
                        onClick={handleReject}
                        disabled={approvalStatus === "rejected"}
                        className={`w-full py-2 px-4 rounded-md flex items-center justify-center ${
                          approvalStatus === "rejected"
                            ? "bg-red-100 text-red-800 cursor-not-allowed"
                            : "bg-red-600 text-white hover:bg-red-700"
                        }`}
                      >
                        <X size={16} className="mr-2" />
                        {approvalStatus === "rejected" ? "Rejected" : "Reject Project"}
                      </button>
                    </div>
                  </section>

                  {/* Tags */}
                  <section className="mb-6">
                    <h2 className="text-lg font-bold mb-3">Project Tags</h2>
                    <div className="flex flex-wrap gap-2">
                      {project.tags.map((tag, index) => (
                        <span
                          key={index}
                          className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-sm font-medium text-gray-800"
                        >
                          <Tag size={14} className="mr-1" /> {tag}
                        </span>
                      ))}
                    </div>
                  </section>

                  {/* Team members */}
                  <section className="mb-6">
                    <h2 className="text-lg font-bold mb-3">Team Members</h2>
                    <div className="space-y-3">
                      {project.teamMembers.map((member, index) => (
                        <div key={index} className="p-3 border rounded-md">
                          <p className="font-medium">{member.name}</p>
                          <p className="text-sm text-gray-500">{member.role}</p>
                          <a href={`mailto:${member.email}`} className="text-sm text-[#4b84c7] hover:underline">
                            {member.email}
                          </a>
                        </div>
                      ))}
                    </div>
                  </section>

                  {/* Submission Info */}
                  <section className="mb-6">
                    <h2 className="text-lg font-bold mb-3">Submission Information</h2>
                    <div className="p-3 border rounded-md">
                      <div className="flex justify-between mb-2">
                        <span className="text-sm text-gray-500">Submitted On:</span>
                        <span className="text-sm font-medium">{project.submittedDate}</span>
                      </div>
                      <div className="flex justify-between mb-2">
                        <span className="text-sm text-gray-500">Course:</span>
                        <span className="text-sm font-medium">{project.course}</span>
                      </div>
                      <div className="flex justify-between mb-2">
                        <span className="text-sm text-gray-500">Term:</span>
                        <span className="text-sm font-medium">
                          {project.quarter} {project.year}
                        </span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-sm text-gray-500">Degree:</span>
                        <span className="text-sm font-medium">{project.degree}</span>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  )
}
