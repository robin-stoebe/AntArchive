import Link from "next/link"

export default function Header() {
  return (
    <header className="bg-[#4b84c7] text-white py-6 px-6">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center">
          {/* Main title */}
          <Link href="/" className="text-2xl font-bold hover:text-gray-200 transition-colors">
            UCI ICS Capstone Archive
          </Link>

          {/* Staff Login link */}
          <Link href="/login" className="text-sm text-white hover:text-gray-200 transition-colors">
            Staff Login
          </Link>
        </div>
      </div>
    </header>
  )
}
