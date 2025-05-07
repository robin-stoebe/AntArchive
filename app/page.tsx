import Header from "@/components/header"
import Navigation from "@/components/navigation"
import FeaturedProjectsCarousel from "@/components/featured-projects-carousel"
import Image from "next/image"
import Link from "next/link"

export default function Home() {
  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <Navigation />

      {/* Main Content */}
      <div className="flex-1 flex flex-col items-center bg-gray-50">
        {/* Featured Projects Carousel Section */}
        <div className="bg-[#848484] w-full py-12 px-6">
          <div className="max-w-5xl mx-auto">
            <h2 className="text-3xl font-semibold mb-2 text-white text-center">Capstone Project Archive</h2>
            <p className="text-white text-center mb-6 opacity-90">Featured student projects from our program</p>
            <FeaturedProjectsCarousel />
          </div>
        </div>

        {/* About Section */}
        <div className="w-full max-w-5xl mx-auto py-8 px-6">
          <h2 className="text-2xl font-bold mb-4">About the ICS Capstone Project Archive</h2>
          <p className="text-black leading-relaxed">
            The Capstone Project Archive is a digital archive created to preserve and highlight Capstone Projects from
            the Information and Computer Science (ICS) program at the University of California, Irvine (UCI). 
            Its mission is to celebrate the creativity and innovation of our students, while also providing a 
            platform for showcasing their work to the wider community. The archive features a diverse range 
            of projects, from software applications to hardware prototypes, all developed by students from different majors
            within the ICS program as part of their capstone experience. The archive serves as a testament to the skills
            and knowledge that students acquire throughout their studies, as well as their ability to apply these skills
            to real-world challenges. Each project is accompanied by detailed documentation, including project 
            descriptions, technical specifications, and links. The archive serves as a valuable resource for students, faculty, 
            and industry professionals, providing insights into the latest trends and technologies in the field of computer 
            science. By preserving and sharing these projects, the archive aims to foster collaboration and knowledge sharing 
            within the ICS community and beyond. We invite you to explore the archive and discover the incredible work being 
            done by our students. Whether you are a prospective student, a faculty member, or an industry partner, we hope you 
            find inspiration and valuable insights in the projects featured here. 
          </p>
          <p className="text-black leading-relaxed mt-4">
            Thank you for visiting the archive, and we look forward to sharing our students' achievements with you!
          </p>
        </div>

        {/* Photo Gallery Section */}
        <div className="w-full bg-white py-12 px-6">
          <div className="max-w-5xl mx-auto">
            <h2 className="text-2xl font-bold mb-2 text-center">Project Showcase Gallery</h2>
            <p className="text-gray-600 text-center mb-8">
              Highlights from our capstone project exhibitions and events
            </p>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {/* Gallery Item 1 */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="relative h-64 w-full">
                  <Image
                    src="/placeholder.svg?key=4viw8"
                    alt="Students presenting their project at the expo"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-lg">Spring 2023 Expo</h3>
                  <p className="text-gray-600">
                    Computer Science students presenting their AI-powered healthcare solution
                  </p>
                </div>
              </div>

              {/* Gallery Item 2 */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="relative h-64 w-full">
                  <Image
                    src="/placeholder.svg?key=tfu84"
                    alt="Professor and students discussing a project prototype"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-lg">Project Mentoring Session</h3>
                  <p className="text-gray-600">Professor Chen providing feedback on a Data Science capstone project</p>
                </div>
              </div>

              {/* Gallery Item 3 */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="relative h-64 w-full">
                  <Image
                    src="/placeholder.svg?key=390vd"
                    alt="Student team receiving award for best project"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-lg">Award Ceremony</h3>
                  <p className="text-gray-600">The "Smart City" team receiving the Best Innovation award</p>
                </div>
              </div>

              {/* Gallery Item 4 */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="relative h-64 w-full">
                  <Image
                    src="/placeholder.svg?key=zi8as"
                    alt="Students collaborating on a project"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-lg">Collaborative Development</h3>
                  <p className="text-gray-600">Informatics students working on their UX research project</p>
                </div>
              </div>

              {/* Gallery Item 5 */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="relative h-64 w-full">
                  <Image
                    src="/placeholder.svg?key=e4gh5"
                    alt="Student demonstrating VR project"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-lg">VR Demo Day</h3>
                  <p className="text-gray-600">Game Design students showcasing their virtual reality experience</p>
                </div>
              </div>

              {/* Gallery Item 6 */}
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="relative h-64 w-full">
                  <Image
                    src="/placeholder.svg?key=fxeab"
                    alt="Industry partners evaluating student projects"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-4">
                  <h3 className="font-semibold text-lg">Industry Evaluation</h3>
                  <p className="text-gray-600">Tech industry representatives reviewing Software Engineering projects</p>
                </div>
              </div>
            </div>

            <div className="mt-8 text-center">
              <Link
                href="/gallery"
                className="px-6 py-3 bg-[#4b84c7] text-white rounded-md hover:bg-[#3b6ba0] transition-colors"
              >
                View More Photos
              </Link>
            </div>
          </div>
        </div>
      </div>
    </main>
  )
}
