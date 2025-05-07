"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import {
  X,
  Plus,
  Edit2,
  Check,
  Save,
  ChevronLeft,
  ChevronRight,
  Filter,
  SortAsc,
  Calendar,
  Tag,
  RefreshCw,
} from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"
import Image from "next/image"

export default function GalleryManagement() {
  const router = useRouter()
  const [loading, setLoading] = useState(true)
  const [activeTab, setActiveTab] = useState("current")
  const [editingPhoto, setEditingPhoto] = useState(null)
  const [showEditModal, setShowEditModal] = useState(false)
  const [hasChanges, setHasChanges] = useState(false)
  const [showFilters, setShowFilters] = useState(false)

  // Pagination state
  const [currentPage, setCurrentPage] = useState(1)
  const photosPerPage = 15

  // Filter and sort state
  const [filters, setFilters] = useState({
    event: "",
    year: "",
    quarter: "",
  })
  const [sortBy, setSortBy] = useState("newest") 

  // Sample gallery data 
  const [galleryPhotos, setGalleryPhotos] = useState([
    {
      id: 1,
      title: "Spring 2026 Expo",
      description: "Computer Science students presenting their AI-powered healthcare solution",
      imageUrl: "/placeholder.svg?key=4viw8",
      featured: true,
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
      featured: true,
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
      featured: true,
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
      featured: true,
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
      featured: true,
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
      featured: true,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: false,
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
      featured: true,
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
      featured: true,
      event: "Competition",
      year: "2027",
      quarter: "Fall",
      dateUploaded: "2027-11-15T14:00:00Z",
    },
    {
      id: 21,
      title: "Cybersecurity Workshop",
      description: "Hands-on workshop on cybersecurity best practices",
      imageUrl: "/placeholder.svg?key=cyber01",
      featured: false,
      event: "Workshop",
      year: "2028",
      quarter: "Winter",
      dateUploaded: "2028-02-20T09:30:00Z",
    },
    {
      id: 22,
      title: "Data Visualization Seminar",
      description: "Seminar on effective data visualization techniques",
      imageUrl: "/placeholder.svg?key=data01",
      featured: false,
      event: "Seminar",
      year: "2028",
      quarter: "Spring",
      dateUploaded: "2028-05-05T11:00:00Z",
    },
  ])

  // Available options for metadata
  const eventTypes = Array.from(new Set(galleryPhotos.map((photo) => photo.event))).sort()
  // Hardcoded years from 2025 and beyond
  const years = ["2025", "2026", "2027", "2028"]
  const quarters = ["Fall", "Winter", "Spring", "Summer"]

  // First, add a new state to track the currently viewed photo in the lightbox
  const [lightboxPhoto, setLightboxPhoto] = useState(null)

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

  // Reset to first page when changing tabs, filters, or sort
  useEffect(() => {
    setCurrentPage(1)
  }, [activeTab, filters, sortBy])

  const handleRemovePhoto = (id) => {
    setGalleryPhotos(galleryPhotos.filter((photo) => photo.id !== id))
    setHasChanges(true)

    // Auto-save changes
    setTimeout(() => {
      handleSaveChanges()
    }, 100)
  }

  const handleToggleFeatured = (id) => {
    setGalleryPhotos(galleryPhotos.map((photo) => (photo.id === id ? { ...photo, featured: !photo.featured } : photo)))
    setHasChanges(true)

    // Auto-save changes
    setTimeout(() => {
      handleSaveChanges()
    }, 100)
  }

  const handleEditPhoto = (photo) => {
    setEditingPhoto({ ...photo })
    setShowEditModal(true)
  }

  const handleSaveEdit = () => {
    if (!editingPhoto) return

    setGalleryPhotos(galleryPhotos.map((photo) => (photo.id === editingPhoto.id ? { ...editingPhoto } : photo)))
    setShowEditModal(false)
    setEditingPhoto(null)
    setHasChanges(true)

    // Auto-save changes
    setTimeout(() => {
      handleSaveChanges()
    }, 100)
  }

  const handleAddNewPhoto = () => {
    // Create a new photo with default values
    const newPhoto = {
      id: Math.max(...galleryPhotos.map((p) => p.id)) + 1,
      title: "New Photo",
      description: "Add a description",
      imageUrl: "/placeholder.svg?key=new",
      featured: false,
      event: "Other",
      year: new Date().getFullYear().toString(),
      quarter: "Fall",
      dateUploaded: new Date().toISOString(),
    }

    setEditingPhoto(newPhoto)
    setShowEditModal(true)
  }

  const handleSaveNewPhoto = () => {
    if (!editingPhoto) return

    // Add the new photo to the gallery
    setGalleryPhotos([...galleryPhotos, editingPhoto])
    setShowEditModal(false)
    setEditingPhoto(null)
    setHasChanges(true)

    // Auto-save changes
    setTimeout(() => {
      handleSaveChanges()
    }, 100)
  }

  const handleSaveChanges = () => {
    // In a real app, this would save to a database
    alert("Changes saved successfully!")
    setHasChanges(false)
  }

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
    setSortBy("newest")
  }

  const isFiltered = filters.event || filters.year || filters.quarter || sortBy !== "newest"

  // Filter and sort logic
  const getFilteredAndSortedPhotos = () => {
    let photos = activeTab === "current" ? galleryPhotos.filter((photo) => photo.featured) : galleryPhotos

    // Apply filters
    if (filters.event) {
      photos = photos.filter((photo) => photo.event === filters.event)
    }

    if (filters.year) {
      photos = photos.filter((photo) => photo.year === filters.year)
    }

    if (filters.quarter) {
      photos = photos.filter((photo) => photo.quarter === filters.quarter)
    }

    // Apply sorting
    switch (sortBy) {
      case "newest":
        photos = [...photos].sort((a, b) => new Date(b.dateUploaded) - new Date(a.dateUploaded))
        break
      case "oldest":
        photos = [...photos].sort((a, b) => new Date(a.dateUploaded) - new Date(b.dateUploaded))
        break
      case "a-z":
        photos = [...photos].sort((a, b) => a.title.localeCompare(b.title))
        break
      case "z-a":
        photos = [...photos].sort((a, b) => b.title.localeCompare(a.title))
        break
      default:
        break
    }

    return photos
  }

  // Pagination logic
  const getDisplayedPhotos = () => {
    const filteredAndSortedPhotos = getFilteredAndSortedPhotos()

    // Calculate pagination
    const indexOfLastPhoto = currentPage * photosPerPage
    const indexOfFirstPhoto = indexOfLastPhoto - photosPerPage

    return filteredAndSortedPhotos.slice(indexOfFirstPhoto, indexOfLastPhoto)
  }

  const filteredAndSortedPhotos = getFilteredAndSortedPhotos()
  const totalPhotos = filteredAndSortedPhotos.length
  const totalPages = Math.ceil(totalPhotos / photosPerPage)

  const openLightbox = (photo) => {
    setLightboxPhoto(photo)
  }

  const closeLightbox = () => {
    setLightboxPhoto(null)
  }

  const nextLightboxPhoto = () => {
    if (!lightboxPhoto) return
    const currentIndex = filteredAndSortedPhotos.findIndex((p) => p.id === lightboxPhoto.id)
    if (currentIndex < filteredAndSortedPhotos.length - 1) {
      setLightboxPhoto(filteredAndSortedPhotos[currentIndex + 1])
    }
  }

  const prevLightboxPhoto = () => {
    if (!lightboxPhoto) return
    const currentIndex = filteredAndSortedPhotos.findIndex((p) => p.id === lightboxPhoto.id)
    if (currentIndex > 0) {
      setLightboxPhoto(filteredAndSortedPhotos[currentIndex - 1])
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p>Loading...</p>
      </div>
    )
  }

  const displayedPhotos = getDisplayedPhotos()

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      <div className="flex-1 p-4 bg-gray-50">
        {/* Sticky header with save button */}
        <div className="sticky top-0 z-10 bg-white shadow-md rounded-lg p-3 mb-4 flex justify-between items-center">
          <h1 className="text-xl font-bold">Photo Gallery Management</h1>
          <div className="flex items-center gap-3">
            <button
              onClick={() => setShowFilters(!showFilters)}
              className={`flex items-center px-3 py-1.5 rounded-md transition-colors text-sm ${
                isFiltered ? "bg-blue-100 text-blue-700" : "bg-gray-200 text-gray-700 hover:bg-gray-300"
              }`}
            >
              <Filter className="w-4 h-4 mr-1" />
              {isFiltered ? "Filtered" : "Filter"}
              {isFiltered && (
                <span className="ml-1 bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs">
                  {Object.values(filters).filter(Boolean).length + (sortBy !== "newest" ? 1 : 0)}
                </span>
              )}
            </button>
            <button
              onClick={() => handleAddNewPhoto()}
              className="flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm"
            >
              <Plus className="w-4 h-4 mr-1" />
              Add Photo
            </button>
            <button
              onClick={handleSaveChanges}
              disabled={!hasChanges}
              className={`flex items-center px-3 py-1.5 rounded-md transition-colors text-sm ${
                hasChanges
                  ? "bg-[#4b84c7] text-white hover:bg-[#3b6ba0]"
                  : "bg-gray-200 text-gray-500 cursor-not-allowed"
              }`}
            >
              <Save className="w-4 h-4 mr-1" />
              Save Changes
            </button>
          </div>
        </div>

        <div className="max-w-7xl mx-auto">
          {/* Tabs */}
          <div className="flex border-b mb-4">
            <button
              className={`px-3 py-1.5 text-sm font-medium ${
                activeTab === "current" ? "border-b-2 border-[#4b84c7] text-[#4b84c7]" : "text-gray-500"
              }`}
              onClick={() => setActiveTab("current")}
            >
              Featured Gallery Photos ({galleryPhotos.filter((photo) => photo.featured).length})
            </button>
            <button
              className={`px-3 py-1.5 text-sm font-medium ${
                activeTab === "all" ? "border-b-2 border-[#4b84c7] text-[#4b84c7]" : "text-gray-500"
              }`}
              onClick={() => setActiveTab("all")}
            >
              All Photos ({galleryPhotos.length})
            </button>
          </div>

          {/* Filter Panel */}
          {showFilters && (
            <div className="bg-white rounded-lg shadow-sm p-3 mb-4">
              <div className="flex justify-between items-center mb-3">
                <h3 className="font-medium text-sm">Filter & Sort Photos</h3>
                <button
                  onClick={handleClearFilters}
                  className="text-xs flex items-center text-gray-500 hover:text-gray-700"
                  disabled={!isFiltered}
                >
                  <RefreshCw className="w-3 h-3 mr-1" />
                  Clear All
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
                {/* Event Filter */}
                <div>
                  <label className="block text-xs font-medium text-gray-500 mb-1">
                    <Tag className="w-3 h-3 inline mr-1" />
                    Event Type
                  </label>
                  <select
                    value={filters.event}
                    onChange={(e) => handleFilterChange("event", e.target.value)}
                    className="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                  <label className="block text-xs font-medium text-gray-500 mb-1">
                    <Calendar className="w-3 h-3 inline mr-1" />
                    Year
                  </label>
                  <select
                    value={filters.year}
                    onChange={(e) => handleFilterChange("year", e.target.value)}
                    className="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                  <label className="block text-xs font-medium text-gray-500 mb-1">
                    <Calendar className="w-3 h-3 inline mr-1" />
                    Quarter
                  </label>
                  <select
                    value={filters.quarter}
                    onChange={(e) => handleFilterChange("quarter", e.target.value)}
                    className="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                  >
                    <option value="">All Quarters</option>
                    {quarters.map((quarter) => (
                      <option key={quarter} value={quarter}>
                        {quarter}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Sort Options */}
                <div>
                  <label className="block text-xs font-medium text-gray-500 mb-1">
                    <SortAsc className="w-3 h-3 inline mr-1" />
                    Sort By
                  </label>
                  <select
                    value={sortBy}
                    onChange={(e) => setSortBy(e.target.value)}
                    className="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                  >
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="a-z">A-Z</option>
                    <option value="z-a">Z-A</option>
                  </select>
                </div>
              </div>

              {/* Active Filters */}
              {isFiltered && (
                <div className="mt-3 flex flex-wrap gap-1">
                  {filters.event && (
                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
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
                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
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
                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                      Quarter: {filters.quarter}
                      <button
                        onClick={() => handleFilterChange("quarter", "")}
                        className="ml-1 text-blue-600 hover:text-blue-800"
                      >
                        <X className="w-3 h-3" />
                      </button>
                    </span>
                  )}
                  {sortBy !== "newest" && (
                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                      Sort:{" "}
                      {sortBy === "a-z"
                        ? "A-Z"
                        : sortBy === "z-a"
                          ? "Z-A"
                          : sortBy === "oldest"
                            ? "Oldest First"
                            : "Newest First"}
                      <button onClick={() => setSortBy("newest")} className="ml-1 text-blue-600 hover:text-blue-800">
                        <X className="w-3 h-3" />
                      </button>
                    </span>
                  )}
                </div>
              )}
            </div>
          )}

          {/* Results count */}
          <div className="flex justify-between items-center mb-3">
            <p className="text-sm text-gray-500">
              {totalPhotos === 0 ? "No photos found" : `Showing ${totalPhotos} photo${totalPhotos !== 1 ? "s" : ""}`}
              {isFiltered && " (filtered)"}
            </p>

            {totalPhotos === 0 && isFiltered && (
              <button
                onClick={handleClearFilters}
                className="text-xs flex items-center text-blue-600 hover:text-blue-800"
              >
                <RefreshCw className="w-3 h-3 mr-1" />
                Clear Filters
              </button>
            )}
          </div>

          {/* Photo Grid - Now with smaller cards and 4 columns on large screens */}
          {totalPhotos > 0 ? (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-4">
              {displayedPhotos.map((photo) => (
                <div key={photo.id} className="bg-white rounded-lg shadow-sm overflow-hidden">
                  <div className="relative">
                    <div className="relative h-32 w-full cursor-pointer" onClick={() => openLightbox(photo)}>
                      <Image
                        src={photo.imageUrl || "/placeholder.svg"}
                        alt={photo.title}
                        fill
                        className="object-cover"
                      />
                    </div>
                    <div className="absolute top-1 right-1 flex space-x-1">
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          handleEditPhoto(photo)
                        }}
                        className="bg-blue-500 text-white p-1 rounded-full hover:bg-blue-600 transition-colors"
                        title="Edit photo details"
                      >
                        <Edit2 className="w-3 h-3" />
                      </button>
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          handleRemovePhoto(photo.id)
                        }}
                        className="bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors"
                        title="Remove photo"
                      >
                        <X className="w-3 h-3" />
                      </button>
                    </div>
                  </div>
                  <div className="p-2">
                    <div className="flex justify-between items-start mb-1">
                      <h3 className="font-medium text-sm truncate" title={photo.title}>
                        {photo.title}
                      </h3>
                      <div className="flex items-center text-xs bg-gray-100 px-1.5 py-0.5 rounded ml-1">
                        {photo.quarter.substring(0, 1)}
                        {photo.year.substring(2)}
                      </div>
                    </div>
                    <p className="text-gray-600 text-xs mb-2 line-clamp-2" title={photo.description}>
                      {photo.description}
                    </p>
                    <div className="flex justify-between items-center">
                      <span className="text-xs text-gray-500">{photo.event}</span>
                      <button
                        onClick={() => handleToggleFeatured(photo.id)}
                        className={`text-xs px-2 py-0.5 rounded ${
                          photo.featured ? "bg-yellow-100 text-yellow-800" : "bg-gray-100 text-gray-800"
                        }`}
                      >
                        {photo.featured ? "Featured" : "Add"}
                      </button>
                    </div>
                  </div>
                </div>
              ))}

              {/* Add New Photo Card - Only show on first page and when not filtered */}
              {currentPage === 1 && !isFiltered && (
                <div
                  onClick={() => handleAddNewPhoto()}
                  className="bg-white rounded-lg shadow-sm border-2 border-dashed border-gray-300 flex flex-col items-center justify-center p-4 h-full min-h-[180px] cursor-pointer hover:bg-gray-50 transition-colors"
                >
                  <div className="bg-gray-100 rounded-full p-2 mb-2">
                    <Plus className="w-5 h-5 text-gray-500" />
                  </div>
                  <p className="text-gray-500 font-medium text-sm mb-1">Add New Photo</p>
                  <p className="text-gray-400 text-xs text-center">Upload a new photo</p>
                </div>
              )}
            </div>
          ) : (
            <div className="bg-white rounded-lg shadow-sm p-8 text-center">
              <p className="text-gray-500 mb-4">No photos found matching your filters</p>
              <button
                onClick={handleClearFilters}
                className="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors"
              >
                Clear All Filters
              </button>
            </div>
          )}

          {/* Pagination Controls */}
          {totalPages > 1 && (
            <div className="flex justify-center items-center mb-6 bg-white rounded-lg shadow-sm p-2">
              <button
                onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                disabled={currentPage === 1}
                className={`p-1 rounded-full ${
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
                      className={`w-8 h-8 mx-0.5 rounded-full ${
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
                    className="w-8 h-8 mx-0.5 rounded-full text-gray-700 hover:bg-gray-100"
                  >
                    {totalPages}
                  </button>
                )}
              </div>

              <button
                onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
                disabled={currentPage === totalPages}
                className={`p-1 rounded-full ${
                  currentPage === totalPages ? "text-gray-400 cursor-not-allowed" : "text-gray-700 hover:bg-gray-100"
                }`}
              >
                <ChevronRight className="w-5 h-5" />
              </button>

              <span className="ml-4 text-xs text-gray-500">
                Showing {(currentPage - 1) * photosPerPage + 1}-{Math.min(currentPage * photosPerPage, totalPhotos)} of{" "}
                {totalPhotos} photos
              </span>
            </div>
          )}
        </div>
      </div>

      {/* Floating save button for mobile */}
      <div className="md:hidden fixed bottom-4 right-4 z-20">
        <button
          onClick={handleSaveChanges}
          disabled={!hasChanges}
          className={`flex items-center justify-center p-3 rounded-full shadow-lg ${
            hasChanges ? "bg-[#4b84c7] text-white" : "bg-gray-300 text-gray-500"
          }`}
        >
          <Save className="w-5 h-5" />
        </button>
      </div>

      {/* Edit Photo Modal */}
      {showEditModal && editingPhoto && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div className="p-6">
              <div className="flex justify-between items-center mb-6">
                <h2 className="text-xl font-bold">
                  {editingPhoto.id === Math.max(...galleryPhotos.map((p) => p.id)) + 1
                    ? "Add New Photo"
                    : "Edit Photo Details"}
                </h2>
                <button onClick={() => setShowEditModal(false)} className="text-gray-500 hover:text-gray-700">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <div className="relative h-48 w-full mb-4 bg-gray-100 rounded-lg overflow-hidden">
                    <Image
                      src={editingPhoto.imageUrl || "/placeholder.svg"}
                      alt={editingPhoto.title}
                      fill
                      className="object-cover"
                    />
                  </div>
                  <button className="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Change Image
                  </button>
                </div>

                <div className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input
                      type="text"
                      value={editingPhoto.title}
                      onChange={(e) => setEditingPhoto({ ...editingPhoto, title: e.target.value })}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                      value={editingPhoto.description}
                      onChange={(e) => setEditingPhoto({ ...editingPhoto, description: e.target.value })}
                      rows={3}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                  <select
                    value={editingPhoto.event}
                    onChange={(e) => setEditingPhoto({ ...editingPhoto, event: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    {eventTypes.map((type) => (
                      <option key={type} value={type}>
                        {type}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Year</label>
                  <select
                    value={editingPhoto.year}
                    onChange={(e) => setEditingPhoto({ ...editingPhoto, year: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    {years.map((year) => (
                      <option key={year} value={year}>
                        {year}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                  <select
                    value={editingPhoto.quarter}
                    onChange={(e) => setEditingPhoto({ ...editingPhoto, quarter: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    {quarters.map((quarter) => (
                      <option key={quarter} value={quarter}>
                        {quarter}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="flex items-center mb-4">
                <input
                  type="checkbox"
                  id="featured"
                  checked={editingPhoto.featured}
                  onChange={(e) => setEditingPhoto({ ...editingPhoto, featured: e.target.checked })}
                  className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label htmlFor="featured" className="ml-2 block text-sm text-gray-700">
                  Featured on Homepage
                </label>
              </div>

              <div className="flex justify-end space-x-3">
                <button
                  onClick={() => setShowEditModal(false)}
                  className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                >
                  Cancel
                </button>
                <button
                  onClick={
                    editingPhoto.id === Math.max(...galleryPhotos.map((p) => p.id)) + 1
                      ? handleSaveNewPhoto
                      : handleSaveEdit
                  }
                  className="px-4 py-2 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors flex items-center"
                >
                  <Check className="w-4 h-4 mr-1" />
                  {editingPhoto.id === Math.max(...galleryPhotos.map((p) => p.id)) + 1 ? "Add Photo" : "Save Changes"}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Lightbox Modal */}
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
                    onClick={prevLightboxPhoto}
                    className="bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-colors"
                    disabled={filteredAndSortedPhotos.findIndex((p) => p.id === lightboxPhoto.id) === 0}
                  >
                    <ChevronLeft className="w-5 h-5" />
                  </button>
                  <button
                    onClick={nextLightboxPhoto}
                    className="bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-colors"
                    disabled={
                      filteredAndSortedPhotos.findIndex((p) => p.id === lightboxPhoto.id) ===
                      filteredAndSortedPhotos.length - 1
                    }
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
