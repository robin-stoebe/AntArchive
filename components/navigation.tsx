"use client"

import { useState } from "react"
import Link from "next/link"
import { usePathname } from "next/navigation"
import { Menu, X } from "lucide-react"

export default function Navigation() {
  const pathname = usePathname()
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  // Determine current page based on pathname
  const isOverviewActive = pathname === "/" || pathname === "/index"
  const isProjectsActive = pathname === "/projects" || pathname.startsWith("/projects/")
  const isSubmitActive = pathname === "/submit"

  return (
    <nav className="bg-gray-50 border-b">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Mobile menu button */}
        <div className="flex justify-between items-center md:hidden h-12">
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="text-gray-500 hover:text-[#4b84c7] focus:outline-none"
            aria-label="Toggle menu"
          >
            {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
          <span className="text-gray-700 font-medium">
            {isOverviewActive
              ? "Overview"
              : isProjectsActive
                ? "Projects"
                : isSubmitActive
                  ? "Submit Project"
                  : "UCI ICS Capstone"}
          </span>
          <div className="w-6"></div> {/* Empty div for flex spacing */}
        </div>

        {/* Desktop navigation */}
        <div className="hidden md:flex h-12">
          <div className="flex space-x-8">
            <Link
              href="/"
              className={`${
                isOverviewActive
                  ? "border-[#4b84c7] text-[#4b84c7]"
                  : "border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300"
              } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors`}
            >
              Overview
            </Link>
            <Link
              href="/projects"
              className={`${
                isProjectsActive
                  ? "border-[#4b84c7] text-[#4b84c7]"
                  : "border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300"
              } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors`}
            >
              Projects
            </Link>
            <Link
              href="/submit"
              className={`${
                isSubmitActive
                  ? "border-[#4b84c7] text-[#4b84c7]"
                  : "border-transparent text-gray-500 hover:text-[#4b84c7] hover:border-gray-300"
              } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors`}
            >
              Submit Project
            </Link>
          </div>
        </div>

        {/* Mobile menu */}
        {mobileMenuOpen && (
          <div className="md:hidden">
            <div className="pt-2 pb-3 space-y-1">
              <Link
                href="/"
                className={`${
                  isOverviewActive
                    ? "bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]"
                    : "text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent"
                } block pl-3 pr-4 py-2 text-base font-medium transition-colors`}
                onClick={() => setMobileMenuOpen(false)}
              >
                Overview
              </Link>
              <Link
                href="/projects"
                className={`${
                  isProjectsActive
                    ? "bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]"
                    : "text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent"
                } block pl-3 pr-4 py-2 text-base font-medium transition-colors`}
                onClick={() => setMobileMenuOpen(false)}
              >
                Projects
              </Link>
              <Link
                href="/submit"
                className={`${
                  isSubmitActive
                    ? "bg-[#4b84c7] bg-opacity-10 text-[#4b84c7] border-l-4 border-[#4b84c7]"
                    : "text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent"
                } block pl-3 pr-4 py-2 text-base font-medium transition-colors`}
                onClick={() => setMobileMenuOpen(false)}
              >
                Submit Project
              </Link>
              <Link
                href="/login"
                className="text-gray-500 hover:bg-gray-100 hover:text-[#4b84c7] border-l-4 border-transparent block pl-3 pr-4 py-2 text-base font-medium transition-colors"
                onClick={() => setMobileMenuOpen(false)}
              >
                Staff Login
              </Link>
            </div>
          </div>
        )}
      </div>
    </nav>
  )
}
