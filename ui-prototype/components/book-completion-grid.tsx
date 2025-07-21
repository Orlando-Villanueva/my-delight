"use client"

import { useState } from "react"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Progress } from "@/components/ui/progress"

const bibleBooks = [
  // Old Testament
  { name: "Genesis", chapters: 50, completed: 12, testament: "Old" },
  { name: "Exodus", chapters: 40, completed: 0, testament: "Old" },
  { name: "Leviticus", chapters: 27, completed: 0, testament: "Old" },
  { name: "Numbers", chapters: 36, completed: 0, testament: "Old" },
  { name: "Deuteronomy", chapters: 34, completed: 0, testament: "Old" },
  { name: "Joshua", chapters: 24, completed: 0, testament: "Old" },
  { name: "Judges", chapters: 21, completed: 0, testament: "Old" },
  { name: "Ruth", chapters: 4, completed: 4, testament: "Old" },
  { name: "1 Samuel", chapters: 31, completed: 0, testament: "Old" },
  { name: "2 Samuel", chapters: 24, completed: 0, testament: "Old" },
  { name: "1 Kings", chapters: 22, completed: 0, testament: "Old" },
  { name: "2 Kings", chapters: 25, completed: 0, testament: "Old" },
  { name: "1 Chronicles", chapters: 29, completed: 0, testament: "Old" },
  { name: "2 Chronicles", chapters: 36, completed: 0, testament: "Old" },
  { name: "Ezra", chapters: 10, completed: 0, testament: "Old" },
  { name: "Nehemiah", chapters: 13, completed: 0, testament: "Old" },
  { name: "Esther", chapters: 10, completed: 10, testament: "Old" },
  { name: "Job", chapters: 42, completed: 0, testament: "Old" },
  { name: "Psalms", chapters: 150, completed: 25, testament: "Old" },
  { name: "Proverbs", chapters: 31, completed: 0, testament: "Old" },
  { name: "Ecclesiastes", chapters: 12, completed: 0, testament: "Old" },
  { name: "Song of Songs", chapters: 8, completed: 0, testament: "Old" },
  { name: "Isaiah", chapters: 66, completed: 0, testament: "Old" },
  { name: "Jeremiah", chapters: 52, completed: 0, testament: "Old" },
  { name: "Lamentations", chapters: 5, completed: 5, testament: "Old" },
  { name: "Ezekiel", chapters: 48, completed: 0, testament: "Old" },
  { name: "Daniel", chapters: 12, completed: 0, testament: "Old" },
  { name: "Hosea", chapters: 14, completed: 0, testament: "Old" },
  { name: "Joel", chapters: 3, completed: 0, testament: "Old" },
  { name: "Amos", chapters: 9, completed: 0, testament: "Old" },
  { name: "Obadiah", chapters: 1, completed: 0, testament: "Old" },
  { name: "Jonah", chapters: 4, completed: 0, testament: "Old" },
  { name: "Micah", chapters: 7, completed: 0, testament: "Old" },
  { name: "Nahum", chapters: 3, completed: 0, testament: "Old" },
  { name: "Habakkuk", chapters: 3, completed: 0, testament: "Old" },
  { name: "Zephaniah", chapters: 3, completed: 0, testament: "Old" },
  { name: "Haggai", chapters: 2, completed: 0, testament: "Old" },
  { name: "Zechariah", chapters: 14, completed: 0, testament: "Old" },
  { name: "Malachi", chapters: 4, completed: 0, testament: "Old" },

  // New Testament
  { name: "Matthew", chapters: 28, completed: 5, testament: "New" },
  { name: "Mark", chapters: 16, completed: 0, testament: "New" },
  { name: "Luke", chapters: 24, completed: 0, testament: "New" },
  { name: "John", chapters: 21, completed: 21, testament: "New" },
  { name: "Acts", chapters: 28, completed: 0, testament: "New" },
  { name: "Romans", chapters: 16, completed: 0, testament: "New" },
  { name: "1 Corinthians", chapters: 16, completed: 0, testament: "New" },
  { name: "2 Corinthians", chapters: 13, completed: 0, testament: "New" },
  { name: "Galatians", chapters: 6, completed: 0, testament: "New" },
  { name: "Ephesians", chapters: 6, completed: 0, testament: "New" },
  { name: "Philippians", chapters: 4, completed: 0, testament: "New" },
  { name: "Colossians", chapters: 4, completed: 0, testament: "New" },
  { name: "1 Thessalonians", chapters: 5, completed: 0, testament: "New" },
  { name: "2 Thessalonians", chapters: 3, completed: 0, testament: "New" },
  { name: "1 Timothy", chapters: 6, completed: 0, testament: "New" },
  { name: "2 Timothy", chapters: 4, completed: 0, testament: "New" },
  { name: "Titus", chapters: 3, completed: 0, testament: "New" },
  { name: "Philemon", chapters: 1, completed: 1, testament: "New" },
  { name: "Hebrews", chapters: 13, completed: 0, testament: "New" },
  { name: "James", chapters: 5, completed: 0, testament: "New" },
  { name: "1 Peter", chapters: 5, completed: 0, testament: "New" },
  { name: "2 Peter", chapters: 3, completed: 0, testament: "New" },
  { name: "1 John", chapters: 5, completed: 0, testament: "New" },
  { name: "2 John", chapters: 1, completed: 0, testament: "New" },
  { name: "3 John", chapters: 1, completed: 0, testament: "New" },
  { name: "Jude", chapters: 1, completed: 0, testament: "New" },
  { name: "Revelation", chapters: 22, completed: 0, testament: "New" },
]

export function BookCompletionGrid() {
  const [selectedTestament, setSelectedTestament] = useState<"Old" | "New">("Old")

  const getCompletionStatus = (completed: number, total: number) => {
    if (completed === 0) return "not-started"
    if (completed === total) return "completed"
    return "in-progress"
  }

  const getStatusColor = (status: string) => {
    switch (status) {
      case "completed":
        return "bg-[#66CC99] text-white border-[#66CC99]"
      case "in-progress":
        return "bg-[#3366CC] text-white border-[#3366CC]"
      default:
        return "bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-[#D1D7E0] dark:border-gray-600 hover:border-[#3366CC]/30"
    }
  }

  const filteredBooks = bibleBooks.filter((book) => book.testament === selectedTestament)
  const testamentStats = {
    total: filteredBooks.length,
    completed: filteredBooks.filter((book) => book.completed === book.chapters).length,
    inProgress: filteredBooks.filter((book) => book.completed > 0 && book.completed < book.chapters).length,
  }

  const overallProgress = Math.round(
    (filteredBooks.reduce((acc, book) => acc + book.completed, 0) /
      filteredBooks.reduce((acc, book) => acc + book.chapters, 0)) *
      100,
  )

  return (
    <Card className="bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 transition-colors">
      <CardHeader className="pb-4">
        <div className="space-y-4">
          {/* Title and Toggle Row */}
          <div className="flex items-start justify-between">
            <CardTitle className="text-lg lg:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
              Bible Reading Progress
            </CardTitle>

            {/* Testament Toggle */}
            <div className="flex bg-[#F5F7FA] dark:bg-gray-700 rounded-lg p-1 ml-4 flex-shrink-0">
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setSelectedTestament("Old")}
                className={`px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] ${
                  selectedTestament === "Old"
                    ? "bg-[#3366CC] text-white shadow-sm"
                    : "text-gray-600 dark:text-gray-300 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600"
                }`}
              >
                Old
              </Button>
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setSelectedTestament("New")}
                className={`px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] ${
                  selectedTestament === "New"
                    ? "bg-[#3366CC] text-white shadow-sm"
                    : "text-gray-600 dark:text-gray-300 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600"
                }`}
              >
                New
              </Button>
            </div>
          </div>

          {/* Progress Section */}
          <div className="space-y-3">
            {/* Testament Label and Percentage */}
            <div className="flex items-center justify-between">
              <span className="text-base font-medium text-gray-700 dark:text-gray-300 leading-[1.5]">
                {selectedTestament} Testament
              </span>
              <span className="text-lg lg:text-xl font-bold text-[#3366CC] leading-[1.5]">{overallProgress}%</span>
            </div>

            {/* Progress Bar */}
            <Progress value={overallProgress} className="h-3" />

            {/* Stats Summary - Mobile Optimized */}
            <div className="grid grid-cols-3 gap-2 text-center text-sm">
              <div className="bg-[#66CC99]/10 dark:bg-[#66CC99]/20 rounded-lg py-2 px-1">
                <div className="font-bold text-[#66CC99] text-base lg:text-lg leading-[1.5]">
                  {testamentStats.completed}
                </div>
                <div className="text-sm text-gray-600 dark:text-gray-400 leading-tight">completed</div>
              </div>
              <div className="bg-[#3366CC]/10 dark:bg-[#3366CC]/20 rounded-lg py-2 px-1">
                <div className="font-bold text-[#3366CC] text-base lg:text-lg leading-[1.5]">
                  {testamentStats.inProgress}
                </div>
                <div className="text-sm text-gray-600 dark:text-gray-400 leading-tight">in progress</div>
              </div>
              <div className="bg-gray-100 dark:bg-gray-700 rounded-lg py-2 px-1">
                <div className="font-bold text-gray-600 dark:text-gray-300 text-base lg:text-lg leading-[1.5]">
                  {testamentStats.total - testamentStats.completed - testamentStats.inProgress}
                </div>
                <div className="text-sm text-gray-600 dark:text-gray-400 leading-tight">not started</div>
              </div>
            </div>
          </div>
        </div>
      </CardHeader>

      <CardContent className="pt-0">
        {/* Books Grid */}
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
          {filteredBooks.map((book) => {
            const status = getCompletionStatus(book.completed, book.chapters)
            const percentage = Math.round((book.completed / book.chapters) * 100)

            return (
              <div
                key={book.name}
                className={`relative p-3 rounded-lg border-2 text-center transition-all duration-200 hover:shadow-md cursor-pointer group ${getStatusColor(status)}`}
                title={`${book.name}: ${book.completed}/${book.chapters} chapters (${percentage}%)`}
              >
                <div className="font-semibold text-sm mb-1 truncate leading-[1.5]">{book.name}</div>
                <div className="text-sm opacity-90 mb-2 leading-[1.5]">{percentage}%</div>

                {/* Mini Progress Bar for In-Progress Books */}
                {status === "in-progress" && (
                  <div className="w-full bg-white/30 rounded-full h-1">
                    <div
                      className="bg-white h-1 rounded-full transition-all duration-300"
                      style={{ width: `${percentage}%` }}
                    />
                  </div>
                )}

                {/* Completion Badge */}
                {status === "completed" && (
                  <div className="absolute -top-1 -right-1 w-4 h-4 bg-[#66CC99] rounded-full flex items-center justify-center">
                    <div className="w-2 h-2 bg-white rounded-full" />
                  </div>
                )}
              </div>
            )
          })}
        </div>

        {/* Legend */}
        <div className="flex items-center justify-center space-x-6 mt-6 pt-4 border-t border-[#D1D7E0] dark:border-gray-600">
          <div className="flex items-center space-x-2">
            <div className="w-3 h-3 bg-[#66CC99] rounded border-2 border-[#66CC99]"></div>
            <span className="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">Completed</span>
          </div>
          <div className="flex items-center space-x-2">
            <div className="w-3 h-3 bg-[#3366CC] rounded border-2 border-[#3366CC]"></div>
            <span className="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">In Progress</span>
          </div>
          <div className="flex items-center space-x-2">
            <div className="w-3 h-3 bg-white dark:bg-gray-800 rounded border-2 border-[#D1D7E0] dark:border-gray-600"></div>
            <span className="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">Not Started</span>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
