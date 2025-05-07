"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import Link from "next/link"
import { ArrowLeft, ExternalLink, Download, Calendar, Users, Tag, Award, GraduationCap, BookOpen } from 'lucide-react'
import Header from "@/components/header"
import Navigation from "@/components/navigation"

// Sample project data (in a real app, this would come from a database)
const projectTags = ["Web Development", "Healthcare", "Machine Learning", "Education", "Mobile Apps", "Sustainability"]

interface TeamMember {
  name: string
  role: string
  email: string
}

interface ProjectData {
  id: string
  title: string
  description: string
  longDescription: string
  quarter: string
  year: string
  degree: string
  professor: string
  course: string
  sponsor: {
    name: string
    organization: string
    email: string
  }
  teamMembers: TeamMember[]
  tags: string[]
  links: string[]
  isAwardWinner: boolean
  rank?: number
  files: {
    name: string
    type: string
    size: string
    url: string
  }[]
}

export default function ProjectDetailPage() {
  const params = useParams()
  const router = useRouter()
  const [project, setProject] = useState<ProjectData | null>(null)

  useEffect(() => {
    // In a real app, this would be an API call to fetch the project data
    const mockProject: ProjectData = {
      id: params.id as string,
      title: "Smart Health Monitoring System",
      description: "A comprehensive health monitoring system for elderly patients using IoT devices and AI.",
      longDescription: `This capstone project developed a comprehensive health monitoring system for elderly patients, leveraging IoT devices and artificial intelligence to provide real-time health tracking and emergency alerts.

The system includes wearable sensors that monitor vital signs such as heart rate, blood pressure, and body temperature. These sensors connect to a central hub in the patient's home, which processes the data and sends it to a cloud-based platform for analysis.

Machine learning algorithms analyze the data to detect anomalies and predict potential health issues before they become critical. The system also includes a mobile application for caregivers and family members, allowing them to monitor the patient's health status remotely and receive alerts in case of emergencies.

The project addresses the growing need for remote healthcare solutions, especially for elderly patients who prefer to age in place. By providing continuous monitoring and early detection of health issues, the system aims to improve patient outcomes, reduce hospitalizations, and provide peace of mind for caregivers.`,
      quarter: "Spring",
      year: "2025",
      degree: "BS",
      professor: "Dr. Jane Smith",
      course: "Computer Science",
      sponsor: {
        name: "John Doe",
        organization: "HealthTech Innovations",
        email: "john.doe@healthtech.com",
      },
      teamMembers: [
        { name: "Alice Johnson", role: "Team Lead", email: "alice@uci.edu" },
        { name: "Bob Williams", role: "Backend Developer", email: "bob@uci.edu" },
        { name: "Charlie Brown", role: "Frontend Developer", email: "charlie@uci.edu" },
        { name: "Diana Miller", role: "Data Scientist", email: "diana@uci.edu" },
      ],
      tags: ["Healthcare", "IoT", "Artificial Intelligence", "Mobile Apps"],
      links: ["https://project-website.com", "https://github.com/project-repo"],
      isAwardWinner: true,
      rank: 1,
      files: [
        {
          name: "Final Report.pdf",
          type: "PDF",
          size: "2.4 MB",
          url: "#",
        },
        {
          name: "Project Presentation.pptx",
          type: "PowerPoint",
          size: "5.1 MB",
          url: "#",
        },
        {
          name: "Demo Video.mp4",
          type: "Video",
          size: "18.7 MB",
          url: "#",
        },
      ],
    }
    setProject(mockProject)
  }, [params.id])

  if (!project) {
    return null; 
  }

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      <div className="flex-1 bg-gray-50">
        {/* Back button */}
        <div className="bg-white border-b">
          <div className="max-w-6xl mx-auto px-6 py-3">
            <button onClick={() => router.back()} className="flex items-center text-[#4b84c7] hover:underline">
              <ArrowLeft size={16} className="mr-1" /> Back to Projects
            </button>
          </div>
        </div>

        <div className="max-w-6xl mx-auto px-6 py-8">
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
                {project.isAwardWinner && (
                  <div className="bg-[#f8e858] text-black px-4 py-2 rounded-full font-bold flex items-center mt-2 sm:mt-0">
                    <Award size={16} className="mr-1" /> #{project.rank} Award Winner
                  </div>
                )}
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
                </div>

                {/* Sidebar - 1/3 width on desktop */}
                <div>
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

                  {/* Professor */}
                  <section className="mb-6">
                    <h2 className="text-lg font-bold mb-3">Faculty Advisor</h2>
                    <div className="p-3 border rounded-md">
                      <p className="font-medium">{project.professor}</p>
                      <p className="text-sm text-gray-500">Faculty Advisor</p>
                    </div>
                  </section>

                  {/* Sponsor */}
                  <section className="mb-6">
                    <h2 className="text-lg font-bold mb-3">Project Sponsor</h2>
                    <div className="p-3 border rounded-md">
                      <p className="font-medium">{project.sponsor.name}</p>
                      <p className="text-sm text-gray-500">{project.sponsor.organization}</p>
                      <a href={`mailto:${project.sponsor.email}`} className="text-sm text-[#4b84c7] hover:underline">
                        {project.sponsor.email}
                      </a>
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
