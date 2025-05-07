"use client"

import { useState } from "react"
import { Plus, X, Upload, CheckCircle, FileUp, ChevronDown } from "lucide-react"
import Header from "@/components/header"
import Navigation from "@/components/navigation"

// List of ICS courses
const majors = [
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

// Project tags
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

export default function SubmitProjectPage() {
  const [description, setDescription] = useState("")
  const [teamMembers, setTeamMembers] = useState([{ name: "", role: "", email: "" }])
  const [links, setLinks] = useState([""])
  const [professor, setProfessor] = useState("")
  const [term, setTerm] = useState("")
  const [projectLevel, setProjectLevel] = useState("")
  const [major, setMajor] = useState("")
  const [showMajorDropdown, setShowMajorDropdown] = useState(false)
  const [selectedTags, setSelectedTags] = useState<string[]>([])
  const [showTagsDropdown, setShowTagsDropdown] = useState(false)

  const addTag = (tag: string) => {
    if (!selectedTags.includes(tag)) {
      setSelectedTags([...selectedTags, tag])
    }
  }

  const removeTag = (tag: string) => {
    setSelectedTags(selectedTags.filter((t) => t !== tag))
  }

  const addTeamMember = () => {
    setTeamMembers([...teamMembers, { name: "", role: "", email: "" }])
  }

  const removeTeamMember = (index: number) => {
    setTeamMembers(teamMembers.filter((_, i) => i !== index))
  }

  const addLink = () => {
    setLinks([...links, ""])
  }

  const removeLink = (index: number) => {
    setLinks(links.filter((_, i) => i !== index))
  }

  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      {/* Form Content */}
      <div className="flex-1 flex flex-col items-center bg-gray-50">
        <div className="w-full max-w-3xl mx-auto py-8 px-6">
          <h2 className="text-2xl font-bold text-center mb-8 text-[#4b84c7]">Submit Your Capstone Project</h2>

          <form className="space-y-10">
            {/* Project Title */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">1. What is your capstone project's title?</label>
              <input
                type="text"
                placeholder="Enter your answer..."
                className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
              />
            </div>

            {/* Project Description */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                2. Describe your capstone project in 1-2 sentences:
              </label>
              <div className="relative">
                <textarea
                  placeholder="Eg: AntArchive is a website that consists of all the past, current, and future capstone projects. It includes various views like the admin, student, and professor view..."
                  className="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent h-32"
                  maxLength={200}
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                ></textarea>
                <div className="absolute bottom-2 right-2 text-sm text-gray-500">{description.length}/200</div>
              </div>
            </div>

            {/* Capstone Professor */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                3. Who is your capstone project advisor/professor?
              </label>
              <input
                type="text"
                placeholder="Enter professor's name..."
                className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                value={professor}
                onChange={(e) => setProfessor(e.target.value)}
              />
            </div>

            {/* Term and Year */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                4. Which academic term is this project for?
              </label>
              <input
                type="text"
                placeholder="E.g., Spring 2023"
                className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                value={term}
                onChange={(e) => setTerm(e.target.value)}
              />
            </div>

            {/* Project Degree */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                5. What degree is this capstone project for?
              </label>
              <div className="flex flex-wrap gap-4">
                <label className="inline-flex items-center">
                  <input
                    type="radio"
                    name="projectDegree"
                    value="BS"
                    checked={projectLevel === "BS"}
                    onChange={() => setProjectLevel("BS")}
                    className="mr-2"
                  />
                  BS
                </label>
                <label className="inline-flex items-center">
                  <input
                    type="radio"
                    name="projectDegree"
                    value="BA"
                    checked={projectLevel === "BA"}
                    onChange={() => setProjectLevel("BA")}
                    className="mr-2"
                  />
                  BA
                </label>
                <label className="inline-flex items-center">
                  <input
                    type="radio"
                    name="projectDegree"
                    value="Professional Master's"
                    checked={projectLevel === "Professional Master's"}
                    onChange={() => setProjectLevel("Professional Master's")}
                    className="mr-2"
                  />
                  Professional Master's
                </label>
                <label className="inline-flex items-center">
                  <input
                    type="radio"
                    name="projectDegree"
                    value="MS"
                    checked={projectLevel === "MS"}
                    onChange={() => setProjectLevel("MS")}
                    className="mr-2"
                  />
                  MS
                </label>
                <label className="inline-flex items-center">
                  <input
                    type="radio"
                    name="projectDegree"
                    value="PhD"
                    checked={projectLevel === "PhD"}
                    onChange={() => setProjectLevel("PhD")}
                    className="mr-2"
                  />
                  PhD
                </label>
              </div>
            </div>

            {/* Course */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">6. What is your primary course?</label>
              <div className="relative">
                <button
                  type="button"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-left flex justify-between items-center text-sm"
                  onClick={() => setShowMajorDropdown(!showMajorDropdown)}
                >
                  {major || "Select your course..."}
                  <ChevronDown className="h-4 w-4" />
                </button>
                {showMajorDropdown && (
                  <div className="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto">
                    {majors.map((m) => (
                      <button
                        key={m}
                        type="button"
                        className="block w-full text-left px-4 py-2 hover:bg-gray-100"
                        onClick={() => {
                          setMajor(m)
                          setShowMajorDropdown(false)
                        }}
                      >
                        {m}
                      </button>
                    ))}
                  </div>
                )}
              </div>
            </div>

            {/* Project Tags */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                7. Select tags that describe your project:
              </label>

              {/* Selected tags display */}
              <div className="flex flex-wrap gap-2 mb-2">
                {selectedTags.map((tag) => (
                  <div key={tag} className="bg-gray-100 rounded-full px-3 py-1 text-sm flex items-center">
                    {tag}
                    <button
                      type="button"
                      onClick={() => removeTag(tag)}
                      className="ml-2 text-gray-500 hover:text-gray-700"
                    >
                      <X size={14} />
                    </button>
                  </div>
                ))}
              </div>

              {/* Tags dropdown */}
              <div className="relative">
                <button
                  type="button"
                  className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-left flex justify-between items-center text-sm"
                  onClick={() => setShowTagsDropdown(!showTagsDropdown)}
                >
                  {selectedTags.length > 0 ? `${selectedTags.length} tags selected` : "Select tags..."}
                  <ChevronDown className="h-4 w-4" />
                </button>
                {showTagsDropdown && (
                  <div className="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto">
                    <div className="p-2 border-b border-gray-200 sticky top-0 bg-white">
                      <p className="text-sm text-gray-500">Select all that apply</p>
                    </div>
                    <div className="grid grid-cols-2 gap-1 p-2">
                      {projectTags.map((tag) => (
                        <button
                          key={tag}
                          type="button"
                          className={`text-left px-3 py-2 text-sm rounded ${
                            selectedTags.includes(tag) ? "bg-[#4b84c7] text-white" : "hover:bg-gray-100"
                          }`}
                          onClick={() => {
                            if (selectedTags.includes(tag)) {
                              removeTag(tag)
                            } else {
                              addTag(tag)
                            }
                          }}
                        >
                          {tag}
                        </button>
                      ))}
                    </div>
                    <div className="p-2 border-t border-gray-200 sticky bottom-0 bg-white">
                      <button
                        type="button"
                        className="w-full py-2 bg-[#4b84c7] text-white rounded text-sm"
                        onClick={() => setShowTagsDropdown(false)}
                      >
                        Done
                      </button>
                    </div>
                  </div>
                )}
              </div>
              <p className="text-sm text-gray-500">
                Select at least 1 tag and up to 5 tags that best describe your project.
              </p>
            </div>

            {/* Team Members - update the question number */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">8. Enter your team members:</label>

              {/* Team members list with max height and scrolling */}
              <div className={`space-y-4 ${teamMembers.length > 5 ? "max-h-96 overflow-y-auto pr-2" : ""}`}>
                {teamMembers.map((member, index) => (
                  <div
                    key={index}
                    className="space-y-2 relative border border-gray-200 p-3 pr-10 rounded-md bg-gray-50"
                  >
                    <input
                      type="text"
                      placeholder="Enter name..."
                      className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                      value={member.name}
                      onChange={(e) => {
                        const newMembers = [...teamMembers]
                        newMembers[index].name = e.target.value
                        setTeamMembers(newMembers)
                      }}
                    />
                    <input
                      type="text"
                      placeholder="Enter role..."
                      className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                      value={member.role}
                      onChange={(e) => {
                        const newMembers = [...teamMembers]
                        newMembers[index].role = e.target.value
                        setTeamMembers(newMembers)
                      }}
                    />
                    <input
                      type="email"
                      placeholder="Enter UCI email..."
                      className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                      value={member.email}
                      onChange={(e) => {
                        const newMembers = [...teamMembers]
                        newMembers[index].email = e.target.value
                        setTeamMembers(newMembers)
                      }}
                    />
                    {teamMembers.length > 1 && (
                      <button
                        type="button"
                        onClick={() => removeTeamMember(index)}
                        className="absolute top-3 right-3 p-1 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full"
                        aria-label="Remove team member"
                      >
                        <X size={18} />
                      </button>
                    )}
                  </div>
                ))}
              </div>

              {/* Team member count indicator */}
              <div className="flex justify-between items-center pt-2">
                <button
                  type="button"
                  onClick={addTeamMember}
                  className="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
                >
                  <Plus size={16} /> Add a team member
                </button>
                <span className="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border">
                  {teamMembers.length} team member{teamMembers.length !== 1 ? "s" : ""}
                </span>
              </div>
            </div>

            {/* Sponsor Information */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                9. Enter your information about your sponsor:
              </label>
              <input
                type="text"
                placeholder="Enter name..."
                className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
              />
              <input
                type="text"
                placeholder="Organization name..."
                className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
              />
              <input
                type="email"
                placeholder="Sponsor's email..."
                className="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
              />
            </div>

            {/* Project Image */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                10. Choose an image representing your capstone project:
              </label>
              <button
                type="button"
                className="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
              >
                <FileUp size={16} /> Choose a file
              </button>
            </div>

            {/* Project Websites */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                11. Enter any websites used for your capstone project:
              </label>
              {links.map((link, index) => (
                <div key={index} className="flex items-center gap-2">
                  <input
                    type="url"
                    placeholder="Enter your answer..."
                    className="flex-1 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#4b84c7] focus:border-transparent text-sm"
                    value={link}
                    onChange={(e) => {
                      const newLinks = [...links]
                      newLinks[index] = e.target.value
                      setLinks(newLinks)
                    }}
                  />
                  {links.length > 1 && (
                    <button
                      type="button"
                      onClick={() => removeLink(index)}
                      className="p-1 text-gray-500 hover:text-gray-700"
                    >
                      <X size={20} />
                    </button>
                  )}
                </div>
              ))}
              <button
                type="button"
                onClick={addLink}
                className="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
              >
                <Plus size={16} /> Add a link
              </button>
            </div>

            {/* Project Video */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                12. Choose a video that represents your capstone project (optional):
              </label>
              <button
                type="button"
                className="flex items-center gap-2 bg-[#4b84c7] text-white py-1.5 px-3 rounded-md hover:bg-[#3b6ba0] transition-colors text-sm"
              >
                <FileUp size={16} /> Choose a file
              </button>
            </div>

            {/* Project Files */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">
                13. Enter up to 5 files about your capstone project:
              </label>
              <div className="border-2 border-dashed border-gray-300 rounded-lg p-12 flex flex-col items-center justify-center text-center">
                <Upload size={48} className="text-[#4b84c7] mb-2" />
                <p className="text-gray-700">Upload any file(s)</p>
              </div>
            </div>

            {/* Submit Form */}
            <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-100 space-y-2">
              <label className="text-base font-semibold text-gray-800">14. Submit form for approval:</label>
              <div className="flex justify-center">
                <button
                  type="submit"
                  className="flex items-center justify-center gap-2 bg-[#4b84c7] text-white py-2 px-6 rounded-md hover:bg-[#3b6ba0] transition-colors font-medium"
                >
                  Submit for Approval <CheckCircle size={20} />
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>
  )
}
