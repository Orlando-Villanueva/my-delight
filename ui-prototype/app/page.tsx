"use client"

import { useState } from "react"
import { DashboardLayout } from "../components/dashboard-layout"
import { StreakCard } from "../components/streak-card"
import { CalendarVisualization } from "../components/calendar-visualization"
import { SummaryStats } from "../components/summary-stats"
import { BookCompletionGrid } from "../components/book-completion-grid"
import { LogReadingModal } from "../components/log-reading-modal"
import { ThemeProvider } from "../components/theme-provider"

// Sample data - in a real app this would come from your backend
const mockData = {
  currentStreak: 5, // Updated to match actual consecutive days from calendar
  longestStreak: 28,
  totalChapters: 156,
  booksStarted: 8,
  booksCompleted: 3,
  firstReadingDate: "2024-01-15",
  lastReadingDate: "2024-06-20",
  readingHistory: [
    // June 2024 readings - Updated with more varied chapter counts for better color intensity
    {
      date: "2024-06-20",
      book: "Matthew",
      chapter: 7,
      notes: "Golden Rule and wise/foolish builders",
      chaptersRead: 3, // Medium intensity
    },
    {
      date: "2024-06-19",
      book: "Matthew",
      chapter: 6,
      notes: "Lord's Prayer and treasures in heaven",
      chaptersRead: 1, // Light intensity
    },
    {
      date: "2024-06-18",
      book: "Matthew",
      chapter: 5,
      notes: "Sermon on the Mount - very inspiring",
      chaptersRead: 7, // Very high intensity - exceptional day
    },
    {
      date: "2024-06-17",
      book: "Matthew",
      chapter: 4,
      notes: "Jesus calls his disciples",
      chaptersRead: 2, // Medium-light intensity
    },
    {
      date: "2024-06-16",
      book: "Matthew",
      chapter: 3,
      notes: "John the Baptist",
      chaptersRead: 4, // High intensity
    },
    // Gap on June 15th - breaks previous streak
    {
      date: "2024-06-14",
      book: "Matthew",
      chapter: 2,
      notes: "Flight to Egypt",
      chaptersRead: 1, // Light intensity
    },
    {
      date: "2024-06-13",
      book: "Matthew",
      chapter: 1,
      notes: "Genealogy of Jesus",
      chaptersRead: 5, // Very high intensity
    },
    {
      date: "2024-06-12",
      book: "Malachi",
      chapter: 4,
      notes: "Final chapter of Old Testament",
      chaptersRead: 6, // Exceptional intensity
    },
    {
      date: "2024-06-11",
      book: "Malachi",
      chapter: 3,
      notes: "Tithing and offerings",
      chaptersRead: 2, // Medium-light intensity
    },
    {
      date: "2024-06-10",
      book: "Malachi",
      chapter: 2,
      notes: "Unfaithful priests",
      chaptersRead: 3, // Medium intensity
    },
    {
      date: "2024-06-09",
      book: "Malachi",
      chapter: 1,
      notes: "God's love for Israel",
      chaptersRead: 1, // Light intensity
    },
    // Gap on June 8th
    {
      date: "2024-06-07",
      book: "Zechariah",
      chapter: 14,
      notes: "The Lord will be king",
      chaptersRead: 4, // High intensity
    },
    {
      date: "2024-06-06",
      book: "Zechariah",
      chapter: 13,
      notes: "Cleansing from sin",
      chaptersRead: 2, // Medium-light intensity
    },
    {
      date: "2024-06-05",
      book: "Zechariah",
      chapter: 12,
      notes: "Jerusalem's enemies destroyed",
      chaptersRead: 1, // Light intensity
    },
    {
      date: "2024-06-04",
      book: "Zechariah",
      chapter: 11,
      notes: "Two shepherds",
      chaptersRead: 8, // Exceptional day - highest intensity
    },
    {
      date: "2024-06-03",
      book: "Zechariah",
      chapter: 10,
      notes: "The Lord will care for Judah",
      chaptersRead: 3, // Medium intensity
    },
    // Gap on June 2nd
    {
      date: "2024-06-01",
      book: "Zechariah",
      chapter: 9,
      notes: "The coming of Zion's king",
      chaptersRead: 2, // Medium-light intensity
    },

    // May 2024 readings with varying chapter counts
    {
      date: "2024-05-31",
      book: "Zechariah",
      chapter: 8,
      notes: "The Lord promises to bless Jerusalem",
      chaptersRead: 1,
    },
    { date: "2024-05-30", book: "Zechariah", chapter: 7, notes: "Justice and mercy, not fasting", chaptersRead: 3 },
    { date: "2024-05-29", book: "Zechariah", chapter: 6, notes: "Four chariots", chaptersRead: 2 },
    { date: "2024-05-28", book: "Zechariah", chapter: 5, notes: "The flying scroll", chaptersRead: 1 },
    { date: "2024-05-26", book: "Zechariah", chapter: 4, notes: "Gold lampstand and two olive trees", chaptersRead: 4 },
    { date: "2024-05-25", book: "Zechariah", chapter: 3, notes: "Clean garments for the high priest", chaptersRead: 1 },
    { date: "2024-05-24", book: "Zechariah", chapter: 2, notes: "A man with a measuring line", chaptersRead: 2 },
    { date: "2024-05-23", book: "Zechariah", chapter: 1, notes: "A call to return to the Lord", chaptersRead: 1 },
    { date: "2024-05-21", book: "Haggai", chapter: 2, notes: "The promised glory of the new house", chaptersRead: 3 },
    { date: "2024-05-20", book: "Haggai", chapter: 1, notes: "A call to build the house of the Lord", chaptersRead: 2 },
    { date: "2024-05-18", book: "Zephaniah", chapter: 3, notes: "The remnant of Israel", chaptersRead: 1 },
    { date: "2024-05-17", book: "Zephaniah", chapter: 2, notes: "Judgment on the nations", chaptersRead: 6 },
    { date: "2024-05-16", book: "Zephaniah", chapter: 1, notes: "The great day of the Lord", chaptersRead: 1 },
    { date: "2024-05-14", book: "Habakkuk", chapter: 3, notes: "Habakkuk's prayer", chaptersRead: 2 },
    { date: "2024-05-13", book: "Habakkuk", chapter: 2, notes: "The righteous live by faith", chaptersRead: 1 },
    { date: "2024-05-12", book: "Habakkuk", chapter: 1, notes: "Habakkuk's complaint", chaptersRead: 3 },
    { date: "2024-05-10", book: "Nahum", chapter: 3, notes: "Woe to Nineveh", chaptersRead: 1 },
    { date: "2024-05-09", book: "Nahum", chapter: 2, notes: "Nineveh to fall", chaptersRead: 2 },
    { date: "2024-05-08", book: "Nahum", chapter: 1, notes: "The Lord's anger against Nineveh", chaptersRead: 1 },
    { date: "2024-05-06", book: "Micah", chapter: 7, notes: "Israel's misery", chaptersRead: 4 },
    { date: "2024-05-05", book: "Micah", chapter: 6, notes: "The Lord's case against Israel", chaptersRead: 1 },
    { date: "2024-05-04", book: "Micah", chapter: 5, notes: "A ruler from Bethlehem", chaptersRead: 2 },
    { date: "2024-05-02", book: "Micah", chapter: 4, notes: "The mountain of the Lord", chaptersRead: 1 },
    { date: "2024-05-01", book: "Micah", chapter: 3, notes: "Leaders and prophets rebuked", chaptersRead: 5 },
  ],
}

export default function Dashboard() {
  const [isLogModalOpen, setIsLogModalOpen] = useState(false)

  return (
    <ThemeProvider defaultTheme="light">
      <DashboardLayout onLogReading={() => setIsLogModalOpen(true)}>
        <div className="space-y-6">
          {/* Main Dashboard Layout */}
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {/* Left Column - Main Content */}
            <div className="lg:col-span-3 space-y-6">
              {/* Top Stats Row */}
              <div className="grid grid-cols-1 md:grid-cols-5 gap-6">
                {/* Streak Card - More Prominent */}
                <div className="md:col-span-2">
                  <StreakCard currentStreak={mockData.currentStreak} longestStreak={mockData.longestStreak} />
                </div>

                {/* Stats Panel - Compact */}
                <div className="md:col-span-3">
                  <SummaryStats
                    totalChapters={mockData.totalChapters}
                    booksStarted={mockData.booksStarted}
                    booksCompleted={mockData.booksCompleted}
                    firstReadingDate={mockData.firstReadingDate}
                    lastReadingDate={mockData.lastReadingDate}
                  />
                </div>
              </div>

              {/* Mobile Calendar - Shows only on mobile, above Book Progress */}
              <div className="lg:hidden">
                <CalendarVisualization readingHistory={mockData.readingHistory} />
              </div>

              {/* Book Completion Grid */}
              <BookCompletionGrid />
            </div>

            {/* Right Column - Desktop Sidebar */}
            <div className="hidden lg:block lg:col-span-1 space-y-6">
              <CalendarVisualization readingHistory={mockData.readingHistory} />

              {/* Recent Activity */}
              <div className="bg-white dark:bg-gray-800 rounded-lg border border-[#D1D7E0] dark:border-gray-700 p-4 transition-colors">
                <h3 className="font-semibold text-[#4A5568] dark:text-gray-200 mb-3">Recent Readings</h3>
                <div className="space-y-2">
                  {mockData.readingHistory.slice(0, 5).map((entry, index) => (
                    <div key={index} className="text-sm">
                      <div className="font-medium text-[#4A5568] dark:text-gray-200">
                        {entry.book} {entry.chapter}
                      </div>
                      <div className="text-gray-500 dark:text-gray-400 text-xs">
                        {new Date(entry.date).toLocaleDateString()}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Log Reading Modal */}
          <LogReadingModal isOpen={isLogModalOpen} onClose={() => setIsLogModalOpen(false)} />
        </div>
      </DashboardLayout>
    </ThemeProvider>
  )
}
