"use client"

import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Plus, BookOpen, Target, TrendingUp } from "lucide-react"

interface QuickActionsProps {
  onLogReading: () => void
}

export function QuickActions({ onLogReading }: QuickActionsProps) {
  return (
    <Card className="bg-gradient-to-r from-[#3366CC]/5 to-[#66CC99]/5 border border-[#3366CC]/20">
      <CardContent className="p-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-4">
            <div className="flex items-center space-x-2">
              <BookOpen className="w-5 h-5 text-[#3366CC]" />
              <span className="font-medium text-[#4A5568]">Ready to log today's reading?</span>
            </div>
            <div className="hidden sm:flex items-center space-x-4 text-sm text-gray-600">
              <div className="flex items-center space-x-1">
                <Target className="w-4 h-4" />
                <span>Keep your streak alive</span>
              </div>
              <div className="flex items-center space-x-1">
                <TrendingUp className="w-4 h-4" />
                <span>Build consistency</span>
              </div>
            </div>
          </div>
          <Button
            onClick={onLogReading}
            className="bg-[#3366CC] hover:bg-[#2952A3] text-white px-6 py-2 rounded-lg flex items-center space-x-2"
          >
            <Plus className="w-4 h-4" />
            <span>Log Reading</span>
          </Button>
        </div>
      </CardContent>
    </Card>
  )
}
