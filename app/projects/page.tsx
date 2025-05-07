"use client"

import { useState, useEffect, useRef } from "react"
import { Search, ChevronDown, ArrowUpDown, X } from "lucide-react"
import Link from "next/link"
import Header from "@/components/header"
import Navigation from "@/components/navigation"

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

const projectTags = [
  "Accessibility",
  "Agriculture",
  "AR/VR",
  "Art & Design",
  "Artificial Intelligence",
  "Blockchain",
  "Cybersecurity",
  "Data Science",
  "Education",
  "Entertainment",
  "Environmental",
  "Finance",
  "Fitness",
  "Food Service",
  "Game Development",
  "Healthcare",
  "IoT",
  "Machine Learning",
  "Medical",
  "Mobile Apps",
  "Music",
  "Restaurant",
  "Retail",
  "Science",
  "Social Impact",
  "Sports",
  "Sustainability",
  "Transportation",
  "Travel",
  "Web Development",
]

// Sample professors
const professors = [
  "Dr. Jane Smith",
  "Dr. John Doe",
  "Dr. Emily Johnson",
  "Dr. Michael Brown",
  "Dr. Sarah Williams",
  "Dr. David Miller",
  "Dr. Lisa Davis",
  "Dr. Robert Wilson",
  "Dr. Jennifer Taylor",
  "Dr. Richard Anderson",
]

// Sample partners/sponsors
const partners = [
  "Google",
  "Microsoft",
  "Apple",
  "Amazon",
  "Meta",
  "IBM",
  "Intel",
  "Cisco",
  "Oracle",
  "Adobe",
  "UCI Health",
  "UCI Medical Center",
  "City of Irvine",
  "Orange County",
  "NASA JPL",
]

const degrees = ["BS", "BA", "Professional Master's", "MS", "PhD"]

export default function ProjectsPage() {
  // State for filters and sorting
  const [showAwardWinners, setShowAwardWinners] = useState(false)
  const [sortBy, setSortBy] = useState<"newest" | "oldest" | "alphabetical">("alphabetical")

  // State for dropdown visibility
  const [openDropdown, setOpenDropdown] = useState<string | null>(null)

  // State for filter values
  const [selectedYear, setSelectedYear] = useState<string | null>(null)
  const [selectedQuarter, setSelectedQuarter] = useState<string | null>(null)
  const [selectedDegree, setSelectedDegree] = useState<string | null>(null)
  const [selectedCourse, setSelectedCourse] = useState<string | null>(null)
  const [selectedProfessor, setSelectedProfessor] = useState<string | null>(null)
  const [selectedTags, setSelectedTags] = useState<string[]>([])
  const [selectedPartner, setSelectedPartner] = useState<string | null>(null)

  // Refs for dropdown containers
  const dropdownRefs = useRef<{ [key: string]: HTMLDivElement | null }>({})

  // Close dropdowns when clicking outside
  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (
        openDropdown &&
        dropdownRefs.current[openDropdown] &&
        !dropdownRefs.current[openDropdown]?.contains(event.target as Node)
      ) {
        setOpenDropdown(null)
      }
    }

    document.addEventListener("mousedown", handleClickOutside)
    return () => {
      document.removeEventListener("mousedown", handleClickOutside)
    }
  }, [openDropdown])

  // Toggle dropdown visibility
  const toggleDropdown = (dropdown: string) => {
    setOpenDropdown(openDropdown === dropdown ? null : dropdown)
  }

  const allProjects = Array(12)
    .fill(null)
    .map((_, i) => {
      // Index for degrees
      let degree
      if (i % 5 === 0) degree = "BS"
      else if (i % 5 === 1) degree = "BA"
      else if (i % 5 === 2) degree = "Professional Master's"
      else if (i % 5 === 3) degree = "MS"
      else degree = "PhD"

      return {
        id: i + 1,
        title: "Title",
        description: "Project Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        isAwardWinner: i < 3, 
        rank: i < 3 ? i + 1 : null, 
        date: new Date(2025, i % 4, 15), // Random dates in 2025
        year: "2025",
        quarter: ["Winter", "Spring", "Summer", "Fall"][i % 4],
        degree,
        course: courses[i % courses.length],
        professor: professors[i % professors.length],
        partner: partners[i % partners.length],
        tags: [projectTags[i % projectTags.length], projectTags[(i + 7) % projectTags.length]],
      }
    })

  // Apply all filters
  let filteredProjects = [...allProjects]

  // Apply award winners filter if selected
  if (showAwardWinners) {
    filteredProjects = filteredProjects.filter((project) => project.isAwardWinner)
  }

  // Apply year filter if selected
  if (selectedYear) {
    filteredProjects = filteredProjects.filter((project) => project.year === selectedYear)
  }

  // Apply quarter filter if selected
  if (selectedQuarter) {
    filteredProjects = filteredProjects.filter((project) => project.quarter === selectedQuarter)
  }

  // Apply degree filter if selected
  if (selectedDegree) {
    filteredProjects = filteredProjects.filter((project) => project.degree === selectedDegree)
  }

  // Apply course filter if selected
  if (selectedCourse) {
    filteredProjects = filteredProjects.filter((project) => project.course === selectedCourse)
  }

  // Apply professor filter if selected
  if (selectedProfessor) {
    filteredProjects = filteredProjects.filter((project) => project.professor === selectedProfessor)
  }

  // Apply tags filter if selected
  if (selectedTags.length > 0) {
    filteredProjects = filteredProjects.filter((project) => selectedTags.some((tag) => project.tags.includes(tag)))
  }

  // Apply partner filter if selected
  if (selectedPartner) {
    filteredProjects = filteredProjects.filter((project) => project.partner === selectedPartner)
  }

  // Limit to 8 projects when not filtering
  if (
    !showAwardWinners &&
    !selectedYear &&
    !selectedQuarter &&
    !selectedDegree &&
    !selectedCourse &&
    !selectedProfessor &&
    selectedTags.length === 0 &&
    !selectedPartner
  ) {
    filteredProjects = filteredProjects.slice(0, 8)
  }

  const sortedProjects = [...filteredProjects].sort((a, b) => {
    if (showAwardWinners && a.isAwardWinner && b.isAwardWinner) {
      return (a.rank || 999) - (b.rank || 999) 
    }

    if (sortBy === "newest") return b.date.getTime() - a.date.getTime()
    if (sortBy === "oldest") return a.date.getTime() - b.date.getTime()
    if (sortBy === "alphabetical") return a.title.localeCompare(b.title)
    return 0
  })

  // Available years for filtering
  const years = ["2025", "2026", "2027"]

  // Available quarters for filtering
  const quarters = ["Fall", "Winter", "Spring", "Summer"]

  // Projects Heading
  const projectsHeading = `${selectedYear || ""}${selectedYear ? " " : ""}${selectedQuarter ? `${selectedQuarter} ` : ""}${
    selectedDegree ? `${selectedDegree} ` : ""
  }${showAwardWinners ? "Award-Winning" : "Archived"} Capstone Projects`

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      {/* Projects Content */}
      <div className="flex-1 flex flex-col items-center bg-gray-50">
        <div className="w-full max-w-6xl mx-auto py-8 px-6">
          {/* Search and Sort Row */}
          <div className="flex flex-wrap justify-between items-center mb-6">
            {/* Search Bar */}
            <div className="relative w-full sm:w-[400px] max-w-full">
              <input
                type="text"
                placeholder="Search..."
                className="w-full py-2 px-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
              />
              <Search className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            </div>

            {/* Sort Dropdown */}
            <div className="relative mt-4 sm:mt-0" ref={(el) => (dropdownRefs.current["sort"] = el)}>
              <button
                className="flex items-center gap-1 py-2 px-4 rounded-md border border-gray-300 bg-white"
                onClick={() => toggleDropdown("sort")}
              >
                <ArrowUpDown className="w-4 h-4 mr-1" />
                Sort by: {sortBy === "newest" ? "Newest" : sortBy === "oldest" ? "Oldest" : "A-Z"}
                <ChevronDown className="w-4 h-4 ml-1" />
              </button>

              {openDropdown === "sort" && (
                <div className="absolute right-0 z-10 mt-1 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1">
                    <button
                      onClick={() => {
                        setSortBy("newest")
                        setOpenDropdown(null)
                      }}
                      className={`block px-4 py-2 text-sm w-full text-left ${
                        sortBy === "newest" ? "bg-gray-100 text-gray-900" : "text-gray-700"
                      }`}
                    >
                      Newest First
                    </button>
                    <button
                      onClick={() => {
                        setSortBy("oldest")
                        setOpenDropdown(null)
                      }}
                      className={`block px-4 py-2 text-sm w-full text-left ${
                        sortBy === "oldest" ? "bg-gray-100 text-gray-900" : "text-gray-700"
                      }`}
                    >
                      Oldest First
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Filters */}
          <div className="flex flex-wrap gap-3 mb-8 items-center">
            {/* Year Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["year"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedYear ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("year")}
              >
                Year: {selectedYear || "All"} <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "year" && (
                <div className="absolute left-0 z-10 mt-1 w-40 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1 max-h-60 overflow-auto">
                    <button
                      onClick={() => {
                        setSelectedYear(null)
                        setOpenDropdown(null)
                      }}
                      className="block px-4 py-2 text-sm w-full text-left text-gray-700 hover:bg-gray-100"
                    >
                      All Years
                    </button>
                    {years.map((year) => (
                      <button
                        key={year}
                        onClick={() => {
                          setSelectedYear(year)
                          setOpenDropdown(null)
                        }}
                        className={`block px-4 py-2 text-sm w-full text-left ${
                          selectedYear === year ? "bg-gray-100 text-gray-900" : "text-gray-700 hover:bg-gray-50"
                        }`}
                      >
                        {year}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Quarter Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["quarter"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedQuarter ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("quarter")}
              >
                Quarter: {selectedQuarter || "All"} <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "quarter" && (
                <div className="absolute left-0 z-10 mt-1 w-40 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1">
                    <button
                      onClick={() => {
                        setSelectedQuarter(null)
                        setOpenDropdown(null)
                      }}
                      className="block px-4 py-2 text-sm w-full text-left text-gray-700 hover:bg-gray-100"
                    >
                      All Quarters
                    </button>
                    {quarters.map((quarter) => (
                      <button
                        key={quarter}
                        onClick={() => {
                          setSelectedQuarter(quarter)
                          setOpenDropdown(null)
                        }}
                        className={`block px-4 py-2 text-sm w-full text-left ${
                          selectedQuarter === quarter ? "bg-gray-100 text-gray-900" : "text-gray-700 hover:bg-gray-50"
                        }`}
                      >
                        {quarter}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Degree Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["degree"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedDegree ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("degree")}
              >
                Degree: {selectedDegree || "All"} <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "degree" && (
                <div className="absolute left-0 z-10 mt-1 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1">
                    <button
                      onClick={() => {
                        setSelectedDegree(null)
                        setOpenDropdown(null)
                      }}
                      className="block px-4 py-2 text-sm w-full text-left text-gray-700 hover:bg-gray-100"
                    >
                      All Degrees
                    </button>
                    {degrees.map((degree) => (
                      <button
                        key={degree}
                        onClick={() => {
                          setSelectedDegree(degree)
                          setOpenDropdown(null)
                        }}
                        className={`block px-4 py-2 text-sm w-full text-left ${
                          selectedDegree === degree ? "bg-gray-100 text-gray-900" : "text-gray-700 hover:bg-gray-50"
                        }`}
                      >
                        {degree}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Course Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["course"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedCourse ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("course")}
              >
                Course: {selectedCourse || "All"} <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "course" && (
                <div className="absolute left-0 z-10 mt-1 w-72 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1 max-h-60 overflow-auto">
                    <button
                      onClick={() => {
                        setSelectedCourse(null)
                        setOpenDropdown(null)
                      }}
                      className="block px-4 py-2 text-sm w-full text-left text-gray-700 hover:bg-gray-100"
                    >
                      All Courses
                    </button>
                    {courses.map((course) => (
                      <button
                        key={course}
                        onClick={() => {
                          setSelectedCourse(course)
                          setOpenDropdown(null)
                        }}
                        className={`block px-4 py-2 text-sm w-full text-left ${
                          selectedCourse === course ? "bg-gray-100 text-gray-900" : "text-gray-700 hover:bg-gray-50"
                        }`}
                      >
                        {course}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Professor Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["professor"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedProfessor ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("professor")}
              >
                Professor: {selectedProfessor ? selectedProfessor.split(" ")[1] : "All"}{" "}
                <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "professor" && (
                <div className="absolute left-0 z-10 mt-1 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1 max-h-60 overflow-auto">
                    <button
                      onClick={() => {
                        setSelectedProfessor(null)
                        setOpenDropdown(null)
                      }}
                      className="block px-4 py-2 text-sm w-full text-left text-gray-700 hover:bg-gray-100"
                    >
                      All Professors
                    </button>
                    {professors.map((professor) => (
                      <button
                        key={professor}
                        onClick={() => {
                          setSelectedProfessor(professor)
                          setOpenDropdown(null)
                        }}
                        className={`block px-4 py-2 text-sm w-full text-left ${
                          selectedProfessor === professor
                            ? "bg-gray-100 text-gray-900"
                            : "text-gray-700 hover:bg-gray-50"
                        }`}
                      >
                        {professor}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Tags Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["tags"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedTags.length > 0 ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("tags")}
              >
                Tags: {selectedTags.length > 0 ? `${selectedTags.length} selected` : "All"}{" "}
                <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "tags" && (
                <div className="absolute left-0 z-10 mt-1 w-64 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1 max-h-60 overflow-auto">
                    <div className="px-4 py-2 border-b">
                      <div className="flex justify-between items-center">
                        <span className="text-sm font-medium">Select Tags</span>
                        {selectedTags.length > 0 && (
                          <button
                            onClick={() => setSelectedTags([])}
                            className="text-xs text-[#4b84c7] hover:underline"
                          >
                            Clear All
                          </button>
                        )}
                      </div>
                    </div>
                    {projectTags.map((tag) => (
                      <div key={tag} className="px-4 py-2 flex items-center">
                        <input
                          type="checkbox"
                          id={`tag-${tag}`}
                          checked={selectedTags.includes(tag)}
                          onChange={() => {
                            if (selectedTags.includes(tag)) {
                              setSelectedTags(selectedTags.filter((t) => t !== tag))
                            } else {
                              setSelectedTags([...selectedTags, tag])
                            }
                          }}
                          className="mr-2"
                        />
                        <label htmlFor={`tag-${tag}`} className="text-sm text-gray-700 cursor-pointer">
                          {tag}
                        </label>
                      </div>
                    ))}
                    <div className="px-4 py-2 border-t">
                      <button
                        onClick={() => setOpenDropdown(null)}
                        className="w-full py-1 px-2 bg-[#4b84c7] text-white rounded text-sm"
                      >
                        Apply
                      </button>
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Partners Filter */}
            <div className="relative" ref={(el) => (dropdownRefs.current["partners"] = el)}>
              <button
                className={`flex items-center gap-1 py-2 px-4 rounded-full border ${
                  selectedPartner ? "bg-[#4b84c7] text-white" : "border-gray-300 bg-white"
                }`}
                onClick={() => toggleDropdown("partners")}
              >
                Partners: {selectedPartner || "All"} <ChevronDown className="w-4 h-4" />
              </button>

              {openDropdown === "partners" && (
                <div className="absolute left-0 z-10 mt-1 w-48 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                  <div className="py-1 max-h-60 overflow-auto">
                    <button
                      onClick={() => {
                        setSelectedPartner(null)
                        setOpenDropdown(null)
                      }}
                      className="block px-4 py-2 text-sm w-full text-left text-gray-700 hover:bg-gray-100"
                    >
                      All Partners
                    </button>
                    {partners.map((partner) => (
                      <button
                        key={partner}
                        onClick={() => {
                          setSelectedPartner(partner)
                          setOpenDropdown(null)
                        }}
                        className={`block px-4 py-2 text-sm w-full text-left ${
                          selectedPartner === partner ? "bg-gray-100 text-gray-900" : "text-gray-700 hover:bg-gray-50"
                        }`}
                      >
                        {partner}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Award Winners Checkbox */}
            <div className="flex items-center gap-2 ml-2">
              <input
                type="checkbox"
                id="award-winners"
                className="w-4 h-4"
                checked={showAwardWinners}
                onChange={(e) => setShowAwardWinners(e.target.checked)}
              />
              <label htmlFor="award-winners" className="text-sm">
                Show Award Winners
              </label>
            </div>
          </div>

          {/* Active Filters */}
          {(selectedYear ||
            selectedQuarter ||
            selectedDegree ||
            selectedCourse ||
            selectedProfessor ||
            selectedTags.length > 0 ||
            selectedPartner) && (
            <div className="flex flex-wrap gap-2 mb-4">
              <span className="text-sm text-gray-500">Active filters:</span>

              {selectedYear && (
                <span className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Year: {selectedYear}
                  <button onClick={() => setSelectedYear(null)} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              )}

              {selectedQuarter && (
                <span className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Quarter: {selectedQuarter}
                  <button onClick={() => setSelectedQuarter(null)} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              )}

              {selectedDegree && (
                <span className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Degree: {selectedDegree}
                  <button onClick={() => setSelectedDegree(null)} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              )}

              {selectedCourse && (
                <span className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Course: {selectedCourse}
                  <button onClick={() => setSelectedCourse(null)} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              )}

              {selectedProfessor && (
                <span className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Professor: {selectedProfessor.split(" ")[1]}
                  <button onClick={() => setSelectedProfessor(null)} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              )}

              {selectedTags.map((tag) => (
                <span key={tag} className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Tag: {tag}
                  <button onClick={() => setSelectedTags(selectedTags.filter((t) => t !== tag))} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              ))}

              {selectedPartner && (
                <span className="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-xs">
                  Partner: {selectedPartner}
                  <button onClick={() => setSelectedPartner(null)} className="ml-1">
                    <X size={12} />
                  </button>
                </span>
              )}

              <button
                onClick={() => {
                  setSelectedYear(null)
                  setSelectedQuarter(null)
                  setSelectedDegree(null)
                  setSelectedCourse(null)
                  setSelectedProfessor(null)
                  setSelectedTags([])
                  setSelectedPartner(null)
                }}
                className="text-xs text-[#4b84c7] hover:underline"
              >
                Clear all filters
              </button>
            </div>
          )}

          {/* Projects Heading */}
          <h2 className="text-2xl font-bold mb-6">{projectsHeading}</h2>

          {/* Projects Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {sortedProjects.map((project) => (
              <Link
                href={`/projects/${project.id}`}
                key={project.id}
                className="flex flex-col hover:shadow-md transition-shadow rounded-md overflow-hidden"
              >
                <div className="flex justify-between items-center mb-2 p-2">
                  <h3 className="font-medium">Title</h3>
                  {project.isAwardWinner && showAwardWinners && (
                    <div className="bg-[#f8e858] text-black px-2 py-1 rounded-full text-xs font-bold">
                      #{project.rank} Winner
                    </div>
                  )}
                </div>
                <div
                  className={`bg-[#d9d9d9] aspect-square mb-2 ${project.isAwardWinner && showAwardWinners ? "border-2 border-[#f8e858]" : ""}`}
                ></div>
                <div className="p-2">
                  <p className="text-sm mb-2">{project.description}</p>

                  {/* Project metadata */}
                  <div className="mt-auto">
                    <div className="text-xs text-gray-500 mb-1">
                      {project.course} • {project.degree} •{" "}
                      {project.date.toLocaleDateString("en-US", { month: "short", year: "numeric" })}
                    </div>
                    <div className="flex flex-wrap gap-1">
                      {project.tags.map((tag, index) => (
                        <span
                          key={index}
                          className="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-700"
                        >
                          {tag}
                        </span>
                      ))}
                    </div>
                  </div>
                </div>
              </Link>
            ))}
          </div>

          {/* Pagination - Only show when not filtering for award winners */}
          {!showAwardWinners && (
            <div className="flex justify-center gap-2 mt-8">
              <span className="px-3 py-1 font-bold">1</span>
              {[2, 3, 4, 5, 6].map((page) => (
                <Link key={page} href={`/projects?page=${page}`} className="px-3 py-1 hover:underline">
                  {page}
                </Link>
              ))}
              <Link href="/projects?page=2" className="px-3 py-1 hover:underline">
                &gt;
              </Link>
            </div>
          )}
        </div>
      </div>
    </main>
  )
}
