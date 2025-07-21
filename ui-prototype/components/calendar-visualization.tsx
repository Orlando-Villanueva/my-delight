"use client"

import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"

interface ReadingEntry {
  date: string
  book: string
  chapter: number
  notes: string
  chaptersRead: number
}

interface CalendarVisualizationProps {
  readingHistory: ReadingEntry[]
}

export function CalendarVisualization({ readingHistory }: CalendarVisualizationProps) {
  // Generate calendar grid for the current month
  const generateCalendarGrid = () => {
    // Use 2024 instead of current year to match our mock data
    const currentYear = 2024
    const currentMonth = 5 // June (0-indexed, so 5 = June)

    // Get first day of month and number of days
    const firstDay = new Date(currentYear, currentMonth, 1)
    const lastDay = new Date(currentYear, currentMonth + 1, 0)
    const daysInMonth = lastDay.getDate()
    const startingDayOfWeek = firstDay.getDay()

    const days = []

    // Add empty cells for days before month starts
    for (let i = 0; i < startingDayOfWeek; i++) {
      days.push(null)
    }

    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
      const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`
      const dayReadings = readingHistory.filter((entry) => entry.date === dateString)
      const totalChapters = dayReadings.reduce((sum, entry) => sum + entry.chaptersRead, 0)
      const hasReading = dayReadings.length > 0

      days.push({
        day,
        hasReading,
        dateString,
        chaptersRead: totalChapters,
        readings: dayReadings,
      })
    }

    return days
  }

  // Enhanced green intensity system with more granular levels
  const getGreenIntensity = (chaptersRead: number) => {
    if (chaptersRead === 0) return ""

    // More varied intensity levels using #66CC99 as base with better progression
    if (chaptersRead === 1) return "bg-[#66CC99]/20 text-gray-800 hover:bg-[#66CC99]/30" // Very light
    if (chaptersRead === 2) return "bg-[#66CC99]/40 text-gray-800 hover:bg-[#66CC99]/50" // Light
    if (chaptersRead === 3) return "bg-[#66CC99]/60 text-white hover:bg-[#66CC99]/70" // Medium-light
    if (chaptersRead === 4) return "bg-[#66CC99]/75 text-white hover:bg-[#66CC99]/85" // Medium
    if (chaptersRead === 5) return "bg-[#66CC99]/90 text-white hover:bg-[#66CC99]" // Medium-high
    if (chaptersRead === 6) return "bg-[#66CC99] text-white hover:bg-[#5AB88A]" // High
    if (chaptersRead === 7) return "bg-[#5AB88A] text-white hover:bg-[#4DA67A]" // Very high
    if (chaptersRead >= 8) return "bg-[#4DA67A] text-white hover:bg-[#3E8E41]" // Exceptional

    return "bg-[#66CC99] text-white hover:bg-[#5AB88A]" // Default green
  }

  const calendarDays = generateCalendarGrid()
  const monthName = new Date(2024, 5).toLocaleDateString("en-US", { month: "long", year: "numeric" })

  const thisMonthReadings = readingHistory.filter((entry) => {
    const entryDate = new Date(entry.date)
    return entryDate.getMonth() === 5 && entryDate.getFullYear() === 2024 // June 2024
  }).length

  // Updated success rate based on 20 days passed in June and 16 actual reading days
  const successRate = Math.round((thisMonthReadings / 20) * 100) // 16 readings out of 20 days = 80%

  return (
    <Card className="bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 h-fit transition-colors">
      <CardHeader className="pb-3">
        <CardTitle className="text-lg lg:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
          Reading Calendar
        </CardTitle>
        <div className="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">{monthName}</div>
      </CardHeader>
      <CardContent className="space-y-4">
        {/* Calendar Grid */}
        <div>
          <div className="grid grid-cols-7 gap-1 mb-2">
            {["S", "M", "T", "W", "T", "F", "S"].map((day) => (
              <div
                key={day}
                className="text-center text-sm font-medium text-gray-500 dark:text-gray-400 py-1 leading-[1.5]"
              >
                {day}
              </div>
            ))}
          </div>
          <div className="grid grid-cols-7 gap-1">
            {calendarDays.map((day, index) => (
              <div
                key={index}
                className={`aspect-square flex items-center justify-center text-sm rounded-full transition-colors cursor-pointer leading-[1.5] ${
                  day === null
                    ? ""
                    : day.hasReading
                      ? getGreenIntensity(day.chaptersRead)
                      : "bg-[#F5F7FA] dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-[#E2E8F0] dark:hover:bg-gray-600 border border-[#D1D7E0] dark:border-gray-600"
                }`}
                title={
                  day && day.hasReading
                    ? `${monthName.split(" ")[0]} ${day.day} - ${day.chaptersRead} chapter${day.chaptersRead !== 1 ? "s" : ""} read`
                    : day
                      ? `${monthName.split(" ")[0]} ${day.day} - No reading`
                      : ""
                }
              >
                {day?.day}
              </div>
            ))}
          </div>
        </div>

        {/* Monthly Stats */}
        <div className="pt-3 border-t border-[#D1D7E0] dark:border-gray-600">
          <div className="grid grid-cols-2 gap-4 text-center">
            <div>
              <div className="text-lg lg:text-xl font-bold text-[#66CC99] leading-[1.5]">{thisMonthReadings}</div>
              <div className="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">This Month</div>
            </div>
            <div>
              <div className="text-lg lg:text-xl font-bold text-[#3366CC] leading-[1.5]">{successRate}%</div>
              <div className="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">Success Rate</div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
