"use client"

import { useState, useEffect } from "react"
import Link from "next/link"
import { ChevronLeft, ChevronRight } from "lucide-react"

// Sample featured project data 
const featuredProjects = [
  {
    id: 1,
    title: "Smart Health Monitoring System",
    description: "A comprehensive health monitoring system for elderly patients using IoT devices and AI.",
    tags: ["Healthcare", "IoT", "AI"],
    year: "2025",
    quarter: "Spring",
  },
  {
    id: 2,
    title: "Sustainable Campus Initiative",
    description: "An application to track and reduce carbon footprint across the UCI campus.",
    tags: ["Sustainability", "Data Science", "Web Development"],
    year: "2025",
    quarter: "Winter",
  },
  {
    id: 3,
    title: "Augmented Reality Learning Platform",
    description: "An AR platform that enhances educational experiences through interactive 3D models.",
    tags: ["AR/VR", "Education", "Mobile Apps"],
    year: "2024",
    quarter: "Fall",
  },
  {
    id: 4,
    title: "Automated Accessibility Tester",
    description: "A tool that evaluates websites for accessibility compliance and suggests improvements.",
    tags: ["Accessibility", "Web Development", "AI"],
    year: "2024",
    quarter: "Summer",
  },
  {
    id: 5,
    title: "Community Resource Mapper",
    description: "A platform connecting underserved communities with local resources and services.",
    tags: ["Social Impact", "Web Development", "GIS"],
    year: "2024",
    quarter: "Spring",
  },
]

export default function FeaturedProjectsCarousel() {
  const [currentIndex, setCurrentIndex] = useState(0)
  const [autoplay, setAutoplay] = useState(true)

  // Autoplay functionality
  useEffect(() => {
    if (!autoplay) return

    const interval = setInterval(() => {
      setCurrentIndex((prevIndex) => (prevIndex + 1) % featuredProjects.length)
    }, 5000)

    return () => clearInterval(interval)
  }, [autoplay])

  // Navigation functions
  const goToPrevious = () => {
    setCurrentIndex((prevIndex) => (prevIndex === 0 ? featuredProjects.length - 1 : prevIndex - 1))
    setAutoplay(false)
  }

  const goToNext = () => {
    setCurrentIndex((prevIndex) => (prevIndex + 1) % featuredProjects.length)
    setAutoplay(false)
  }

  const goToSlide = (index: number) => {
    setCurrentIndex(index)
    setAutoplay(false)
  }

  const currentProject = featuredProjects[currentIndex]

  return (
    <div className="w-full relative">
      <div className="relative bg-white rounded-lg shadow-md overflow-hidden">
        {/* Project content */}
        <div className="grid md:grid-cols-2 gap-4 p-6">
          {/* Project image */}
          <div className="bg-[#d9d9d9] aspect-video rounded-md flex items-center justify-center">
            <span className="text-gray-500 text-sm">Project Image</span>
          </div>

          {/* Project details */}
          <div className="flex flex-col">
            <div className="mb-2">
              <h3 className="text-xl font-bold">{currentProject.title}</h3>
              <p className="text-sm text-gray-500">
                {currentProject.quarter} {currentProject.year}
              </p>
            </div>

            <p className="text-gray-700 mb-4">{currentProject.description}</p>

            <div className="flex flex-wrap gap-2 mb-4">
              {currentProject.tags.map((tag, index) => (
                <span
                  key={index}
                  className="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-700"
                >
                  {tag}
                </span>
              ))}
            </div>

            <div className="mt-auto">
              <Link
                href={`/projects/${currentProject.id}`}
                className="inline-flex items-center justify-center bg-[#4b84c7] text-white py-2 px-4 rounded hover:bg-[#3b6ba0] transition-colors"
              >
                View Project Details
              </Link>
            </div>
          </div>
        </div>

        {/* Navigation arrows */}
        <button
          onClick={goToPrevious}
          className="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2 shadow-md hover:bg-white"
          aria-label="Previous project"
        >
          <ChevronLeft size={24} />
        </button>

        <button
          onClick={goToNext}
          className="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2 shadow-md hover:bg-white"
          aria-label="Next project"
        >
          <ChevronRight size={24} />
        </button>
      </div>

      {/* Indicator dots */}
      <div className="flex justify-center mt-4 gap-2">
        {featuredProjects.map((_, index) => (
          <button
            key={index}
            onClick={() => goToSlide(index)}
            className={`w-3 h-3 rounded-full ${index === currentIndex ? "bg-white" : "bg-white/50"}`}
            aria-label={`Go to slide ${index + 1}`}
          />
        ))}
      </div>
    </div>
  )
}
