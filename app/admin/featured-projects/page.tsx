"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { Search, ChevronDown, ChevronUp, Star, Trophy, X, ArrowUp, ArrowDown } from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"

// Sample project data
const sampleProjects = Array(20)
  .fill(null)
  .map((_, i) => ({
    id: i + 1,
    title: `Project ${i + 1}: ${["Smart Health Monitor", "Campus Navigation App", "AI Study Assistant", "Virtual Lab Simulator", "Sustainable Energy Tracker"][i % 5]}`,
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum enim sapien, auctor sed mollis vehicula.",
    course: ["Computer Science", "Informatics", "Data Science", "Software Engineering", "Computer Game Science"][i % 5],
    degree: ["BS", "BA", "MS", "PhD", "Professional Master's"][i % 5],
    quarter: ["Fall", "Winter", "Spring", "Summer"][i % 4],
    year: `202${5 + Math.floor(i / 5)}`, // Years from 2025 and beyond
    professor: ["Dr. Smith", "Dr. Johnson", "Dr. Williams", "Dr. Brown", "Dr. Davis"][i % 5],
    isFeatured: i < 5, // First 5 are featured
    isAwardWinner: i < 3, // First 3 are award winners
    rank: i < 3 ? i + 1 : null, // Ranks for top 3
    teamMembers: Array(Math.floor(Math.random() * 4) + 1)
      .fill(null)
      .map((_, j) => `Student ${j + 1}`),
  }))

export default function ManageFeaturedProjectsPage() {
  const router = useRouter()
  const [loading, setLoading] = useState(true)
  const [projects, setProjects] = useState(sampleProjects)
  const [searchTerm, setSearchTerm] = useState("")
  const [filterYear, setFilterYear] = useState<string | null>(null)
  const [filterQuarter, setFilterQuarter] = useState<string | null>(null)
  const [filterCourse, setFilterCourse] = useState<string | null>(null)
  const [sortDirection, setSortDirection] = useState<"asc" | "desc">("asc") 
  const [activeTab, setActiveTab] = useState<"featured" | "awards">("featured")

  // Filter for featured projects
  const featuredProjects = projects.filter((project) => project.isFeatured)

  // Filter for award winners
  const awardWinners = projects
    .filter((project) => project.isAwardWinner)
    .sort((a, b) => (a.rank || 999) - (b.rank || 999))

  // Available years for filtering
  const years = Array.from(new Set(projects.map((project) => project.year))).sort()
  // Available quarters for filtering
  const quarters = ["Fall", "Winter", "Spring", "Summer"]
  // Available courses for filtering 
  const courses = [
    "Bioinformatics",
    "Business Information Management",
    "Computer Game Science",
    "Computer Science",
    "Computer Science and Engineering",
    "Data Science",
    "Digital Information Systems",
    "Game Design and Interactive Media",
    "Health Informatics",
    "Human Computer Interaction and Design",
    "Information and Computer Science",
    "Informatics",
    "Networked Systems",
    "Software Engineering",
    "Statistics",
  ]

  // Filtered projects for selection
  const filteredProjects = projects
    .filter((project) => !project.isFeatured && !project.isAwardWinner) 
    .filter(
      (project) =>
        project.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        project.description.toLowerCase().includes(searchTerm.toLowerCase()),
    )
    .filter((project) => (filterYear ? project.year === filterYear : true))
    .filter((project) => (filterQuarter ? project.quarter === filterQuarter : true))
    .filter((project) => (filterCourse ? project.course === filterCourse : true))
    .sort((a, b) => {
      if (sortDirection === "asc") {
        return a.title.localeCompare(b.title)
      } else {
        return b.title.localeCompare(a.title)
      }
    })

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

  // Function to toggle featured status
  const toggleFeatured = (id: number) => {
    setProjects(
      projects.map((project) => (project.id === id ? { ...project, isFeatured: !project.isFeatured } : project)),
    )
  }

  // Function to toggle award winner status
  const toggleAwardWinner = (id: number) => {
    const project = projects.find((p) => p.id === id)

    if (project) {
      if (project.isAwardWinner) {
        // Remove award winner status
        setProjects(projects.map((p) => (p.id === id ? { ...p, isAwardWinner: false, rank: null } : p)))
      } else {
        // Add as award winner with next available rank
        const nextRank = awardWinners.length + 1
        setProjects(projects.map((p) => (p.id === id ? { ...p, isAwardWinner: true, rank: nextRank } : p)))
      }
    }
  }

  // Function to move award rank up
  const moveRankUp = (id: number) => {
    const project = projects.find((p) => p.id === id)
    if (!project || !project.rank || project.rank <= 1) return

    const currentRank = project.rank
    const targetRank = currentRank - 1

    setProjects(
      projects.map((p) => {
        if (p.id === id) return { ...p, rank: targetRank }
        if (p.rank === targetRank) return { ...p, rank: currentRank }
        return p
      }),
    )
  }

  // Function to move award rank down
  const moveRankDown = (id: number) => {
    const project = projects.find((p) => p.id === id)
    if (!project || !project.rank || project.rank >= awardWinners.length) return

    const currentRank = project.rank
    const targetRank = currentRank + 1

    setProjects(
      projects.map((p) => {
        if (p.id === id) return { ...p, rank: targetRank }
        if (p.rank === targetRank) return { ...p, rank: currentRank }
        return p
      }),
    )
  }

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
          <div className="flex justify-between items-center mb-6">
            <h1 className="text-3xl font-bold">Manage Featured Projects</h1>
            <button
              onClick={() => router.push("/admin")}
              className="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors"
            >
              Back to Dashboard
            </button>
          </div>

          {/* Tabs */}
          <div className="flex border-b mb-6">
            <button
              className={`px-4 py-2 font-medium ${
                activeTab === "featured"
                  ? "border-b-2 border-[#4b84c7] text-[#4b84c7]"
                  : "text-gray-500 hover:text-gray-700"
              }`}
              onClick={() => setActiveTab("featured")}
            >
              Featured Projects
            </button>
            <button
              className={`px-4 py-2 font-medium ${
                activeTab === "awards"
                  ? "border-b-2 border-[#4b84c7] text-[#4b84c7]"
                  : "text-gray-500 hover:text-gray-700"
              }`}
              onClick={() => setActiveTab("awards")}
            >
              Award Winners
            </button>
          </div>

          {/* Featured Projects Tab */}
          {activeTab === "featured" && (
            <div>
              <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-4">Current Featured Projects</h2>
                <p className="text-gray-600 mb-4">
                  These projects will be displayed in the featured carousel on the homepage. You can feature up to 5
                  projects.
                </p>

                {featuredProjects.length === 0 ? (
                  <p className="text-gray-500 italic">No featured projects selected.</p>
                ) : (
                  <div className="max-h-[300px] overflow-y-auto pr-2">
                    <div className="space-y-2">
                      {featuredProjects.map((project) => (
                        <div key={project.id} className="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                          <div className="overflow-hidden">
                            <h3 className="font-medium text-sm truncate">{project.title}</h3>
                            <p className="text-xs text-gray-500 truncate">
                              {project.course} • {project.degree} • {project.quarter} {project.year}
                            </p>
                          </div>
                          <button
                            onClick={() => toggleFeatured(project.id)}
                            className="p-1.5 ml-2 flex-shrink-0 text-red-500 hover:bg-red-50 rounded-full"
                            aria-label="Remove from featured"
                          >
                            <X size={16} />
                          </button>
                        </div>
                      ))}
                    </div>
                  </div>
                )}

                {featuredProjects.length < 5 && (
                  <p className="text-sm text-gray-500 mt-3">
                    You can add {5 - featuredProjects.length} more featured project
                    {featuredProjects.length < 4 ? "s" : ""}.
                  </p>
                )}
              </div>

              <div className="bg-white rounded-lg shadow-md p-6">
                <h2 className="text-xl font-semibold mb-4">Add Featured Projects</h2>

                {/* Search and filters */}
                <div className="flex flex-wrap gap-3 mb-4">
                  <div className="relative flex-grow max-w-md">
                    <input
                      type="text"
                      placeholder="Search projects..."
                      className="w-full py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                    />
                    <Search className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                  </div>

                  <div className="flex flex-wrap gap-2">
                    <div className="relative">
                      <select
                        className="appearance-none py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7] bg-white text-sm"
                        value={filterYear || ""}
                        onChange={(e) => setFilterYear(e.target.value || null)}
                      >
                        <option value="">All Years</option>
                        {years.map((year) => (
                          <option key={year} value={year}>
                            {year}
                          </option>
                        ))}
                      </select>
                      <ChevronDown className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
                    </div>

                    <div className="relative">
                      <select
                        className="appearance-none py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7] bg-white text-sm"
                        value={filterQuarter || ""}
                        onChange={(e) => setFilterQuarter(e.target.value || null)}
                      >
                        <option value="">All Quarters</option>
                        {quarters.map((quarter) => (
                          <option key={quarter} value={quarter}>
                            {quarter}
                          </option>
                        ))}
                      </select>
                      <ChevronDown className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
                    </div>

                    <div className="relative">
                      <select
                        className="appearance-none py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7] bg-white text-sm"
                        value={filterCourse || ""}
                        onChange={(e) => setFilterCourse(e.target.value || null)}
                      >
                        <option value="">All Courses</option>
                        {courses.map((course) => (
                          <option key={course} value={course}>
                            {course}
                          </option>
                        ))}
                      </select>
                      <ChevronDown className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
                    </div>
                  </div>

                  <button
                    onClick={() => setSortDirection(sortDirection === "asc" ? "desc" : "asc")}
                    className="flex items-center gap-1 py-2 px-4 rounded-md border border-gray-300 bg-white text-sm"
                  >
                    Sort: {sortDirection === "asc" ? "A-Z" : "Z-A"}
                    {sortDirection === "asc" ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
                  </button>
                </div>

                {/* Project list with max height and scrolling */}
                {filteredProjects.length === 0 ? (
                  <p className="text-gray-500 italic">No projects found matching your criteria.</p>
                ) : (
                  <div className="max-h-[400px] overflow-y-auto pr-2">
                    <div className="space-y-2">
                      {filteredProjects.map((project) => (
                        <div key={project.id} className="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                          <div className="overflow-hidden">
                            <h3 className="font-medium text-sm truncate">{project.title}</h3>
                            <p className="text-xs text-gray-500 truncate">
                              {project.course} • {project.degree} • {project.quarter} {project.year}
                            </p>
                          </div>
                          <button
                            onClick={() => toggleFeatured(project.id)}
                            disabled={featuredProjects.length >= 5}
                            className={`p-1.5 ml-2 flex-shrink-0 ${
                              featuredProjects.length >= 5
                                ? "text-gray-400 cursor-not-allowed"
                                : "text-green-500 hover:bg-green-50"
                            } rounded-full`}
                            aria-label="Add to featured"
                          >
                            <Star size={16} />
                          </button>
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>
            </div>
          )}

          {/* Award Winners Tab */}
          {activeTab === "awards" && (
            <div>
              <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 className="text-xl font-semibold mb-4">Current Award Winners</h2>
                <p className="text-gray-600 mb-4">
                  These projects will be displayed as award winners. You can reorder them to change their ranking.
                </p>

                {awardWinners.length === 0 ? (
                  <p className="text-gray-500 italic">No award winners selected.</p>
                ) : (
                  <div className="max-h-[300px] overflow-y-auto pr-2">
                    <div className="space-y-2">
                      {awardWinners.map((project) => (
                        <div key={project.id} className="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                          <div className="flex items-center">
                            <div className="bg-[#f8e858] text-black px-3 py-1 rounded-full text-sm font-bold mr-3">
                              #{project.rank}
                            </div>
                            <div>
                              <h3 className="font-medium text-sm truncate">{project.title}</h3>
                              <p className="text-xs text-gray-500 truncate">
                                {project.course} • {project.degree} • {project.quarter} {project.year}
                              </p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            <button
                              onClick={() => moveRankUp(project.id)}
                              disabled={project.rank === 1}
                              className={`p-1 ${
                                project.rank === 1
                                  ? "text-gray-300 cursor-not-allowed"
                                  : "text-gray-500 hover:bg-gray-100"
                              } rounded`}
                              aria-label="Move rank up"
                            >
                              <ArrowUp size={18} />
                            </button>
                            <button
                              onClick={() => moveRankDown(project.id)}
                              disabled={project.rank === awardWinners.length}
                              className={`p-1 ${
                                project.rank === awardWinners.length
                                  ? "text-gray-300 cursor-not-allowed"
                                  : "text-gray-500 hover:bg-gray-100"
                              } rounded`}
                              aria-label="Move rank down"
                            >
                              <ArrowDown size={18} />
                            </button>
                            <button
                              onClick={() => toggleAwardWinner(project.id)}
                              className="p-1 text-red-500 hover:bg-red-50 rounded"
                              aria-label="Remove award"
                            >
                              <X size={18} />
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>

              <div className="bg-white rounded-lg shadow-md p-6">
                <h2 className="text-xl font-semibold mb-4">Add Award Winners</h2>

                {/* Search and filters (same as featured projects) */}
                <div className="flex flex-wrap gap-3 mb-4">
                  <div className="relative flex-grow max-w-md">
                    <input
                      type="text"
                      placeholder="Search projects..."
                      className="w-full py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                    />
                    <Search className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                  </div>

                  <div className="flex flex-wrap gap-2">
                    <div className="relative">
                      <select
                        className="appearance-none py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7] bg-white text-sm"
                        value={filterYear || ""}
                        onChange={(e) => setFilterYear(e.target.value || null)}
                      >
                        <option value="">All Years</option>
                        {years.map((year) => (
                          <option key={year} value={year}>
                            {year}
                          </option>
                        ))}
                      </select>
                      <ChevronDown className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
                    </div>

                    <div className="relative">
                      <select
                        className="appearance-none py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7] bg-white text-sm"
                        value={filterQuarter || ""}
                        onChange={(e) => setFilterQuarter(e.target.value || null)}
                      >
                        <option value="">All Quarters</option>
                        {quarters.map((quarter) => (
                          <option key={quarter} value={quarter}>
                            {quarter}
                          </option>
                        ))}
                      </select>
                      <ChevronDown className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
                    </div>

                    <div className="relative">
                      <select
                        className="appearance-none py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7] bg-white text-sm"
                        value={filterCourse || ""}
                        onChange={(e) => setFilterCourse(e.target.value || null)}
                      >
                        <option value="">All Courses</option>
                        {courses.map((course) => (
                          <option key={course} value={course}>
                            {course}
                          </option>
                        ))}
                      </select>
                      <ChevronDown className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
                    </div>
                  </div>

                  <button
                    onClick={() => setSortDirection(sortDirection === "asc" ? "desc" : "asc")}
                    className="flex items-center gap-1 py-2 px-4 rounded-md border border-gray-300 bg-white text-sm"
                  >
                    Sort: {sortDirection === "asc" ? "A-Z" : "Z-A"}
                    {sortDirection === "asc" ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
                  </button>
                </div>

                {filteredProjects.length === 0 ? (
                  <p className="text-gray-500 italic">No projects found matching your criteria.</p>
                ) : (
                  <div className="max-h-[400px] overflow-y-auto pr-2">
                    <div className="space-y-2">
                      {filteredProjects.map((project) => (
                        <div key={project.id} className="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                          <div className="overflow-hidden">
                            <h3 className="font-medium text-sm truncate">{project.title}</h3>
                            <p className="text-xs text-gray-500 truncate">
                              {project.course} • {project.degree} • {project.quarter} {project.year}
                            </p>
                          </div>
                          <button
                            onClick={() => toggleAwardWinner(project.id)}
                            className="p-1.5 ml-2 flex-shrink-0 text-yellow-500 hover:bg-yellow-50 rounded-full"
                            aria-label="Add as award winner"
                          >
                            <Trophy size={16} />
                          </button>
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>
            </div>
          )}
        </div>
      </div>
    </main>
  )
}
