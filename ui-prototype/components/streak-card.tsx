"use client"

import { Card, CardContent } from "@/components/ui/card"
import { Flame } from "lucide-react"

interface StreakCardProps {
  currentStreak: number
  longestStreak: number
}

export function StreakCard({ currentStreak, longestStreak }: StreakCardProps) {
  return (
    <Card className="bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white border-0 h-full">
      <CardContent className="p-6 flex flex-col justify-center h-full">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg lg:text-xl font-semibold leading-[1.5]">Current Streak</h3>
          <Flame className="w-6 h-6 text-[#FF9933]" />
        </div>
        <div className="text-center flex-1 flex flex-col justify-center">
          <div className="text-4xl lg:text-5xl font-bold mb-2 leading-[1.5]">{currentStreak}</div>
          <div className="text-sm opacity-90 leading-[1.5]">days in a row</div>
        </div>
        <div className="mt-4 pt-4 border-t border-white/20">
          <div className="flex justify-between text-sm leading-[1.5]">
            <span className="opacity-90">Longest streak:</span>
            <span className="font-semibold">{longestStreak} days</span>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}
