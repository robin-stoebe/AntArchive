"use client"

import { useState, useEffect } from "react"
import { Search, ChevronLeft, ChevronRight, Filter, X, RefreshCw } from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"
import Image from "next/image"

export default function GalleryPage() {
  const [currentPage, setCurrentPage] = useState(1)
  const [showFilters, setShowFilters] = useState(false)
  const [lightboxPhoto, setLightboxPhoto] = useState(null)
  const [searchTerm, setSearchTerm] = useState("")
  const photosPerPage = 12

  // Filter state
  const [filters, setFilters] = useState({
    event: "",
    year: "",
    quarter: "",
  })

  // Sample gallery data
  const galleryPhotos = [
    {
      id: 1,
      title: "Spring 2026 Expo",
      description: "Computer Science students presenting their AI-powered healthcare solution",
      imageUrl: "/placeholder.svg?key=4viw8",
      event: "Expo",
      year: "2026",
      quarter: "Spring",
      dateUploaded: "2026-06-15T14:30:00Z",
    },
    {
      id: 2,
      title: "Project Mentoring Session",
      description: "Professor Chen providing feedback on a Data Science capstone project",
      imageUrl: "/placeholder.svg?key=tfu84",
      event: "Mentoring",
      year: "2026",
      quarter: "Winter",
      dateUploaded: "2026-03-22T09:15:00Z",
    },
    {
      id: 3,
      title: "Award Ceremony",
      description: 'The "Smart City" team receiving the Best Innovation award',
      imageUrl: "/placeholder.svg?key=390vd",
      event: "Awards",
      year: "2026",
      quarter: "Spring",
      dateUploaded: "2026-06-20T16:45:00Z",
    },
    {
      id: 4,
      title: "Collaborative Development",
      description: "Informatics students working on their UX research project",
      imageUrl: "/placeholder.svg?key=zi8as",
      event: "Workshop",
      year: "2025",
      quarter: "Fall",
      dateUploaded: "2025-11-05T13:20:00Z",
    },
    {
      id: 5,
      title: "VR Demo Day",
      description: "Game Design students showcasing their virtual reality experience",
      imageUrl: "/placeholder.svg?key=e4gh5",
      event: "Demo",
      year: "2026",
      quarter: "Winter",
      dateUploaded: "2026-02-18T14:00:00Z",
    },
    {
      id: 6,
      title: "Industry Evaluation",
      description: "Tech industry representatives reviewing Software Engineering projects",
      imageUrl: "/placeholder.svg?key=1zphp",
      event: "Evaluation",
      year: "2025",
      quarter: "Spring",
      dateUploaded: "2025-05-25T10:30:00Z",
    },
    {
      id: 7,
      title: "Team Collaboration",
      description: "Students working together on their final presentation",
      imageUrl: "/placeholder.svg?key=spd7v",
      event: "Workshop",
      year: "2025",
      quarter: "Winter",
      dateUploaded: "2025-02-12T09:45:00Z",
    },
    {
      id: 8,
      title: "Faculty Showcase",
      description: "Faculty members reviewing student projects",
      imageUrl: "/placeholder.svg?key=ny9x9",
      event: "Showcase",
      year: "2026",
      quarter: "Fall",
      dateUploaded: "2026-10-08T15:15:00Z",
    },
    {
      id: 9,
      title: "Student Networking Event",
      description: "Students connecting with industry professionals",
      imageUrl: "/placeholder.svg?key=net01",
      event: "Networking",
      year: "2026",
      quarter: "Winter",
      dateUploaded: "2026-01-20T17:30:00Z",
    },
    {
      id: 10,
      title: "Capstone Kickoff",
      description: "Professor introducing the capstone course requirements",
      imageUrl: "/placeholder.svg?key=kick1",
      event: "Orientation",
      year: "2025",
      quarter: "Fall",
      dateUploaded: "2025-09-28T08:00:00Z",
    },
    {
      id: 11,
      title: "Midterm Presentations",
      description: "Teams presenting their progress at the midpoint",
      imageUrl: "/placeholder.svg?key=mid22",
      event: "Presentation",
      year: "2025",
      quarter: "Winter",
      dateUploaded: "2025-02-15T13:00:00Z",
    },
    {
      id: 12,
      title: "Design Workshop",
      description: "UX design workshop for capstone teams",
      imageUrl: "/placeholder.svg?key=des01",
      event: "Workshop",
      year: "2026",
      quarter: "Fall",
      dateUploaded: "2026-09-15T14:30:00Z",
    },
    {
      id: 13,
      title: "Industry Panel",
      description: "Industry experts providing feedback on project ideas",
      imageUrl: "/placeholder.svg?key=pan01",
      event: "Panel",
      year: "2026",
      quarter: "Winter",
      dateUploaded: "2026-02-05T10:00:00Z",
    },
    {
      id: 14,
      title: "Prototype Testing",
      description: "Students conducting usability tests with real users",
      imageUrl: "/placeholder.svg?key=test1",
      event: "Testing",
      year: "2025",
      quarter: "Spring",
      dateUploaded: "2025-04-18T11:30:00Z",
    },
    {
      id: 15,
      title: "Final Rehearsals",
      description: "Teams practicing their final presentations",
      imageUrl: "/placeholder.svg?key=reh01",
      event: "Rehearsal",
      year: "2026",
      quarter: "Spring",
      dateUploaded: "2026-05-28T09:00:00Z",
    },
    {
      id: 16,
      title: "Alumni Mentorship Day",
      description: "Alumni returning to mentor current capstone teams",
      imageUrl: "/placeholder.svg?key=alum1",
      event: "Mentoring",
      year: "2026",
      quarter: "Winter",
      dateUploaded: "2026-02-25T13:45:00Z",
    },
    {
      id: 17,
      title: "Project Showcase",
      description: "End of quarter project showcase with industry partners",
      imageUrl: "/placeholder.svg?key=show1",
      event: "Showcase",
      year: "2025",
      quarter: "Spring",
      dateUploaded: "2025-06-10T16:00:00Z",
    },
    {
      id: 18,
      title: "Technical Workshop",
      description: "Advanced technical workshop on cloud deployment",
      imageUrl: "/placeholder.svg?key=tech1",
      event: "Workshop",
      year: "2026",
      quarter: "Fall",
      dateUploaded: "2026-10-22T10:15:00Z",
    },
    {
      id: 19,
      title: "AI Hackathon",
      description: "Students participating in an AI-focused hackathon",
      imageUrl: "/placeholder.svg?key=hack01",
      event: "Hackathon",
      year: "2027",
      quarter: "Summer",
      dateUploaded: "2027-07-10T12:00:00Z",
    },
    {
      id: 20,
      title: "Robotics Competition",
      description: "Teams showcasing their robotic creations",
      imageUrl: "/placeholder.svg?key=robot01",
      event: "Competition",
      year: "2027",
      quarter: "Fall",
      dateUploaded: "2027-11-15T14:00:00Z",
    },
  ]

  // Options for metadata
  const eventTypes = Array.from(new Set(galleryPhotos.map((photo) => photo.event))).sort()
  const years = ["2025", "2026", "2027", "2028"]
  const quarters = ["Fall", "Winter", "Spring", "Summer"]

  // Reset to first page when changing filters
  useEffect(() => {
    setCurrentPage(1)
  }, [filters, searchTerm])

  const handleFilterChange = (key, value) => {
    setFilters((prev) => ({
      ...prev,
      [key]: value,
    }))
  }

  const handleClearFilters = () => {
    setFilters({
      event: "",
      year: "",
      quarter: "",
    })
    setSearchTerm("")
  }

  const openLightbox = (photo) => {
    setLightboxPhoto(photo)
  }

  const closeLightbox = () => {
    setLightboxPhoto(null)
  }

  // Navigate to next photo in lightbox
  const nextLightboxPhoto = () => {
    if (!lightboxPhoto) return
    const currentIndex = filteredPhotos.findIndex((p) => p.id === lightboxPhoto.id)
    if (currentIndex < filteredPhotos.length - 1) {
      setLightboxPhoto(filteredPhotos[currentIndex + 1])
    }
  }

  // Navigate to previous photo in lightbox
  const prevLightboxPhoto = () => {
    if (!lightboxPhoto) return
    const currentIndex = filteredPhotos.findIndex((p) => p.id === lightboxPhoto.id)
    if (currentIndex > 0) {
      setLightboxPhoto(filteredPhotos[currentIndex - 1])
    }
  }

  // Filter photos based on search and filters
  const filteredPhotos = galleryPhotos.filter((photo) => {
    // Apply search term
    if (
      searchTerm &&
      !photo.title.toLowerCase().includes(searchTerm.toLowerCase()) &&
      !photo.description.toLowerCase().includes(searchTerm.toLowerCase())
    ) {
      return false
    }

    // Apply filters
    if (filters.event && photo.event !== filters.event) return false
    if (filters.year && photo.year !== filters.year) return false
    if (filters.quarter && photo.quarter !== filters.quarter) return false

    return true
  })

  // Sort photos by date (newest first)
  const sortedPhotos = [...filteredPhotos].sort((a, b) => new Date(b.dateUploaded) - new Date(a.dateUploaded))

  // Pagination
  const totalPhotos = sortedPhotos.length
  const totalPages = Math.ceil(totalPhotos / photosPerPage)
  const currentPhotos = sortedPhotos.slice((currentPage - 1) * photosPerPage, currentPage * photosPerPage)

  const isFiltered = filters.event || filters.year || filters.quarter || searchTerm

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      <div className="flex-1 bg-gray-50 py-8 px-6">
        <div className="max-w-7xl mx-auto">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
              <h1 className="text-3xl font-bold mb-2">Project Showcase Gallery</h1>
              <p className="text-gray-600">Explore photos from our capstone project events and exhibitions</p>
            </div>

            <div className="flex items-center gap-3 w-full md:w-auto">
              <div className="relative flex-grow">
                <input
                  type="text"
                  placeholder="Search photos..."
                  className="w-full py-2 px-4 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                />
                <Search className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
              </div>

              <button
                onClick={() => setShowFilters(!showFilters)}
                className={`flex items-center px-3 py-2 rounded-md transition-colors ${
                  isFiltered ? "bg-blue-100 text-blue-700" : "bg-gray-200 text-gray-700 hover:bg-gray-300"
                }`}
              >
                <Filter className="w-4 h-4 mr-2" />
                {isFiltered ? "Filtered" : "Filter"}
                {isFiltered && (
                  <span className="ml-1 bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs">
                    {Object.values(filters).filter(Boolean).length + (searchTerm ? 1 : 0)}
                  </span>
                )}
              </button>
            </div>
          </div>

          {/* Filter Panel */}
          {showFilters && (
            <div className="bg-white rounded-lg shadow-md p-4 mb-6">
              <div className="flex justify-between items-center mb-3">
                <h3 className="font-medium">Filter Photos</h3>
                <button
                  onClick={handleClearFilters}
                  className="text-sm flex items-center text-gray-500 hover:text-gray-700"
                  disabled={!isFiltered}
                >
                  <RefreshCw className="w-3 h-3 mr-1" />
                  Clear All
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {/* Event Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                  <select
                    value={filters.event}
                    onChange={(e) => handleFilterChange("event", e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                  >
                    <option value="">All Events</option>
                    {eventTypes.map((type) => (
                      <option key={type} value={type}>
                        {type}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Year Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Year</label>
                  <select
                    value={filters.year}
                    onChange={(e) => handleFilterChange("year", e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                  >
                    <option value="">All Years</option>
                    {years.map((year) => (
                      <option key={year} value={year}>
                        {year}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Quarter Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                  <select
                    value={filters.quarter}
                    onChange={(e) => handleFilterChange("quarter", e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4b84c7]"
                  >
                    <option value="">All Quarters</option>
                    {quarters.map((quarter) => (
                      <option key={quarter} value={quarter}>
                        {quarter}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              {/* Active Filters */}
              {isFiltered && (
                <div className="mt-3 flex flex-wrap gap-2">
                  {searchTerm && (
                    <span className="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                      Search: {searchTerm}
                      <button onClick={() => setSearchTerm("")} className="ml-1 text-blue-600 hover:text-blue-800">
                        <X className="w-3 h-3" />
                      </button>
                    </span>
                  )}
                  {filters.event && (
                    <span className="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                      Event: {filters.event}
                      <button
                        onClick={() => handleFilterChange("event", "")}
                        className="ml-1 text-blue-600 hover:text-blue-800"
                      >
                        <X className="w-3 h-3" />
                      </button>
                    </span>
                  )}
                  {filters.year && (
                    <span className="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                      Year: {filters.year}
                      <button
                        onClick={() => handleFilterChange("year", "")}
                        className="ml-1 text-blue-600 hover:text-blue-800"
                      >
                        <X className="w-3 h-3" />
                      </button>
                    </span>
                  )}
                  {filters.quarter && (
                    <span className="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                      Quarter: {filters.quarter}
                      <button
                        onClick={() => handleFilterChange("quarter", "")}
                        className="ml-1 text-blue-600 hover:text-blue-800"
                      >
                        <X className="w-3 h-3" />
                      </button>
                    </span>
                  )}
                </div>
              )}
            </div>
          )}

          {/* Results count */}
          <div className="mb-4">
            <p className="text-sm text-gray-500">
              {totalPhotos === 0 ? "No photos found" : `Showing ${totalPhotos} photo${totalPhotos !== 1 ? "s" : ""}`}
              {isFiltered && " (filtered)"}
            </p>
          </div>

          {/* Photo Grid */}
          {totalPhotos > 0 ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
              {currentPhotos.map((photo) => (
                <div
                  key={photo.id}
                  className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
                  onClick={() => openLightbox(photo)}
                >
                  <div className="relative h-48 w-full">
                    <Image src={photo.imageUrl || "/placeholder.svg"} alt={photo.title} fill className="object-cover" />
                  </div>
                  <div className="p-4">
                    <h3 className="font-semibold text-lg mb-1">{photo.title}</h3>
                    <p className="text-gray-600 text-sm mb-2 line-clamp-2">{photo.description}</p>
                    <div className="flex justify-between items-center">
                      <span className="text-xs text-gray-500">{photo.event}</span>
                      <span className="text-xs bg-gray-100 px-2 py-1 rounded-full">
                        {photo.quarter} {photo.year}
                      </span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="bg-white rounded-lg shadow-md p-8 text-center">
              <p className="text-gray-500 mb-4">No photos found matching your search criteria</p>
              <button
                onClick={handleClearFilters}
                className="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors"
              >
                Clear All Filters
              </button>
            </div>
          )}

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="flex justify-center items-center mt-8">
              <button
                onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                disabled={currentPage === 1}
                className={`p-2 rounded-full ${
                  currentPage === 1 ? "text-gray-400 cursor-not-allowed" : "text-gray-700 hover:bg-gray-100"
                }`}
              >
                <ChevronLeft className="w-5 h-5" />
              </button>

              <div className="flex items-center mx-2">
                {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                  // Logic to show current page and nearby pages
                  let pageToShow
                  if (totalPages <= 5) {
                    pageToShow = i + 1
                  } else if (currentPage <= 3) {
                    pageToShow = i + 1
                  } else if (currentPage >= totalPages - 2) {
                    pageToShow = totalPages - 4 + i
                  } else {
                    pageToShow = currentPage - 2 + i
                  }

                  return (
                    <button
                      key={i}
                      onClick={() => setCurrentPage(pageToShow)}
                      className={`w-8 h-8 mx-1 rounded-full ${
                        currentPage === pageToShow ? "bg-[#4b84c7] text-white" : "text-gray-700 hover:bg-gray-100"
                      }`}
                    >
                      {pageToShow}
                    </button>
                  )
                })}

                {/* Show ellipsis if there are more pages */}
                {totalPages > 5 && currentPage < totalPages - 2 && <span className="mx-1 text-gray-500">...</span>}

                {/* Always show last page */}
                {totalPages > 5 && currentPage < totalPages - 2 && (
                  <button
                    onClick={() => setCurrentPage(totalPages)}
                    className="w-8 h-8 mx-1 rounded-full text-gray-700 hover:bg-gray-100"
                  >
                    {totalPages}
                  </button>
                )}
              </div>

              <button
                onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
                disabled={currentPage === totalPages}
                className={`p-2 rounded-full ${
                  currentPage === totalPages ? "text-gray-400 cursor-not-allowed" : "text-gray-700 hover:bg-gray-100"
                }`}
              >
                <ChevronRight className="w-5 h-5" />
              </button>
            </div>
          )}
        </div>
      </div>

      {lightboxPhoto && (
        <div
          className="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4"
          onClick={closeLightbox}
        >
          <div className="relative max-w-4xl max-h-[90vh] overflow-hidden" onClick={(e) => e.stopPropagation()}>
            <div className="relative h-[80vh] w-[80vw] max-w-4xl">
              <Image
                src={lightboxPhoto.imageUrl || "/placeholder.svg"}
                alt={lightboxPhoto.title}
                fill
                className="object-contain"
              />
            </div>

            <div className="absolute top-2 right-2">
              <button
                onClick={closeLightbox}
                className="bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-colors"
              >
                <X className="w-6 h-6" />
              </button>
            </div>

            <div className="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 p-4">
              <h3 className="text-white font-medium text-lg">{lightboxPhoto.title}</h3>
              <p className="text-white text-sm opacity-90">{lightboxPhoto.description}</p>
              <div className="flex justify-between items-center mt-2">
                <div className="text-white text-sm">
                  {lightboxPhoto.event} • {lightboxPhoto.quarter} {lightboxPhoto.year}
                </div>
                <div className="flex space-x-2">
                  <button
                    onClick={(e) => {
                      e.stopPropagation()
                      prevLightboxPhoto()
                    }}
                    className="bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-colors"
                    disabled={filteredPhotos.findIndex((p) => p.id === lightboxPhoto.id) === 0}
                  >
                    <ChevronLeft className="w-5 h-5" />
                  </button>
                  <button
                    onClick={(e) => {
                      e.stopPropagation()
                      nextLightboxPhoto()
                    }}
                    className="bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-colors"
                    disabled={filteredPhotos.findIndex((p) => p.id === lightboxPhoto.id) === filteredPhotos.length - 1}
                  >
                    <ChevronRight className="w-5 h-5" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </main>
  )
}
