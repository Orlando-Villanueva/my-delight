"use client"

import { Card, CardContent } from "@/components/ui/card"
import { Calendar, TrendingUp, BookOpen, Target } from "lucide-react"

interface SummaryStatsProps {
  totalChapters: number
  booksStarted: number
  booksCompleted: number
  firstReadingDate: string
  lastReadingDate: string
}

export function SummaryStats({
  totalChapters,
  booksStarted,
  booksCompleted,
  firstReadingDate,
  lastReadingDate,
}: SummaryStatsProps) {
  // Calculate new metrics based on June 20th, 2024
  const currentDate = new Date(2024, 5, 20) // June 20, 2024
  const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate() // 30 days in June
  const currentDay = currentDate.getDate() // 20th

  // Updated to match actual calendar data - 16 reading days in June
  const readingDaysThisMonth = 16 // From our updated calendar data (including gaps)

  // Reading velocity (chapters per week) - based on recent activity
  const weeksActive = 4 // Approximate weeks of activity
  const recentChapters = 42 // Approximate recent chapters from mock data
  const readingVelocity = (recentChapters / weeksActive).toFixed(1)

  // Bible progress percentage (total chapters in Bible ≈ 1189)
  const totalBibleChapters = 1189
  const bibleProgress = Math.round((totalChapters / totalBibleChapters) * 100)

  // Next milestone - 10-day streak (current is 5, so next reasonable milestone is 10)
  const currentStreak = 5
  const nextStreakMilestone = 10

  return (
    <Card className="bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 h-full transition-colors">
      <CardContent className="p-6 h-full flex items-center">
        <div className="grid grid-cols-2 xl:grid-cols-4 gap-6 w-full">
          {/* This Month Progress */}
          <div className="flex flex-col items-center text-center">
            <div className="p-3 rounded-lg bg-[#3366CC]/10 dark:bg-[#3366CC]/20 mb-3">
              <Calendar className="w-5 h-5 text-[#3366CC]" />
            </div>
            <div className="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
              {readingDaysThisMonth}/{daysInMonth}
            </div>
            <div className="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">This Month</div>
          </div>

          {/* Reading Velocity */}
          <div className="flex flex-col items-center text-center">
            <div className="p-3 rounded-lg bg-[#66CC99]/10 dark:bg-[#66CC99]/20 mb-3">
              <TrendingUp className="w-5 h-5 text-[#66CC99]" />
            </div>
            <div className="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
              {readingVelocity}
            </div>
            <div className="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">Chapters/Week</div>
          </div>

          {/* Bible Progress */}
          <div className="flex flex-col items-center text-center">
            <div className="p-3 rounded-lg bg-[#FF9933]/10 dark:bg-[#FF9933]/20 mb-3">
              <BookOpen className="w-5 h-5 text-[#FF9933]" />
            </div>
            <div className="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
              {bibleProgress}%
            </div>
            <div className="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">Bible Progress</div>
          </div>

          {/* Next Milestone */}
          <div className="flex flex-col items-center text-center">
            <div className="p-3 rounded-lg bg-purple-100 dark:bg-purple-900/30 mb-3">
              <Target className="w-5 h-5 text-purple-600 dark:text-purple-400" />
            </div>
            <div className="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
              {nextStreakMilestone}-day
            </div>
            <div className="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">Next Milestone</div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
