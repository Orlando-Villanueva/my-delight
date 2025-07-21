"use client"

import type React from "react"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { BookOpen, Calendar, BarChart3, User, Menu, X, Plus, Settings, LogOut, Crown } from "lucide-react"
import { ThemeToggle } from "./theme-toggle"

interface DashboardLayoutProps {
  children: React.ReactNode
  onLogReading: () => void
}

export function DashboardLayout({ children, onLogReading }: DashboardLayoutProps) {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)

  const navigationItems = [
    { icon: BookOpen, label: "Dashboard", href: "/", active: true },
    { icon: Calendar, label: "History", href: "/history" },
    { icon: BarChart3, label: "Statistics", href: "/stats" },
    { icon: User, label: "Profile", href: "/profile" },
  ]

  // Mock user data - in a real app this would come from your auth system
  const user = {
    name: "Orlando",
    email: "orlando@email.com",
    avatar: "/placeholder.svg?height=40&width=40",
    plan: "Premium",
    streak: 12,
  }

  return (
    <div className="min-h-screen bg-[#F5F7FA] dark:bg-gray-900 transition-colors">
      {/* Desktop Sidebar */}
      <div className="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
        <div className="flex flex-col flex-grow bg-white dark:bg-gray-800 border-r border-[#D1D7E0] dark:border-gray-700 pt-5 pb-4 overflow-y-auto transition-colors">
          {/* Logo */}
          <div className="flex items-center flex-shrink-0 px-4">
            <div className="flex items-center space-x-2">
              <div className="w-8 h-8 bg-[#3366CC] rounded-lg flex items-center justify-center">
                <BookOpen className="w-5 h-5 text-white" />
              </div>
              <h1 className="text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">Bible Habit</h1>
            </div>
          </div>

          {/* Navigation */}
          <nav className="mt-8 flex-1 px-2 space-y-1">
            {navigationItems.map((item) => (
              <a
                key={item.label}
                href={item.href}
                className={`group flex items-center px-2 py-2 text-base font-medium rounded-md transition-colors leading-[1.5] ${
                  item.active
                    ? "bg-[#3366CC] text-white"
                    : "text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-[#3366CC] dark:hover:text-[#3366CC]"
                }`}
              >
                <item.icon className="mr-3 h-5 w-5" />
                {item.label}
              </a>
            ))}
          </nav>

          {/* User Profile Section - Subtle Design */}
          <div className="flex-shrink-0 px-2 pb-2">
            {/* User Info Card - Neutral and Understated */}
            <div className="bg-gray-50 dark:bg-gray-750 rounded-lg p-3 mb-3 transition-colors border border-gray-100 dark:border-gray-600">
              <div className="flex items-center space-x-3">
                <div className="relative">
                  <img
                    src={user.avatar || "/placeholder.svg"}
                    alt={user.name}
                    className="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600"
                  />
                  {/* Subtle Premium Indicator */}
                  {user.plan === "Premium" && (
                    <div className="absolute -top-0.5 -right-0.5 w-3 h-3 bg-gray-400 dark:bg-gray-500 rounded-full flex items-center justify-center">
                      <Crown className="w-1.5 h-1.5 text-white" />
                    </div>
                  )}
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-base font-medium text-gray-900 dark:text-gray-100 truncate leading-[1.5]">
                    {user.name}
                  </p>
                  <p className="text-sm text-gray-500 dark:text-gray-400 truncate leading-[1.5]">{user.email}</p>
                  {/* Subtle Status Indicators */}
                  <div className="flex items-center space-x-3 mt-1">
                    <div className="flex items-center space-x-1">
                      <div className="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                      <span className="text-sm text-gray-500 dark:text-gray-400 leading-[1.5]">
                        {user.streak} day streak
                      </span>
                    </div>
                    {user.plan === "Premium" && (
                      <span className="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full font-medium leading-[1.5]">
                        Pro
                      </span>
                    )}
                  </div>
                </div>
              </div>
            </div>

            {/* Action Buttons - Subtle with Blue Accents on Hover */}
            <div className="space-y-1">
              <Button
                variant="ghost"
                className="w-full justify-start text-base text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#3366CC] dark:hover:text-[#3366CC] leading-[1.5]"
              >
                <Settings className="mr-3 h-4 w-4" />
                Settings
              </Button>
              <Button
                variant="ghost"
                className="w-full justify-start text-base text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 leading-[1.5]"
              >
                <LogOut className="mr-3 h-4 w-4" />
                Sign Out
              </Button>
            </div>
          </div>
        </div>
      </div>

      {/* Mobile Header */}
      <div className="lg:hidden">
        <div className="flex items-center justify-between h-16 px-4 bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 transition-colors">
          <div className="flex items-center space-x-2">
            <div className="w-8 h-8 bg-[#3366CC] rounded-lg flex items-center justify-center">
              <BookOpen className="w-5 h-5 text-white" />
            </div>
            <h1 className="text-lg sm:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
              Bible Habit
            </h1>
          </div>
          <div className="flex items-center space-x-2">
            <ThemeToggle />
            <Button variant="ghost" size="icon" onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}>
              {isMobileMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
            </Button>
          </div>
        </div>

        {/* Mobile Menu */}
        {isMobileMenuOpen && (
          <div className="bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 transition-colors">
            <nav className="px-2 pt-2 pb-3 space-y-1">
              {navigationItems.map((item) => (
                <a
                  key={item.label}
                  href={item.href}
                  className={`group flex items-center px-3 py-2 text-base font-medium rounded-md transition-colors leading-[1.5] ${
                    item.active
                      ? "bg-[#3366CC] text-white"
                      : "text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-[#3366CC] dark:hover:text-[#3366CC]"
                  }`}
                >
                  <item.icon className="mr-3 h-6 w-6" />
                  {item.label}
                </a>
              ))}
            </nav>

            {/* Mobile User Section - Subtle Design */}
            <div className="px-2 pb-3 border-t border-[#D1D7E0] dark:border-gray-600 mt-3 pt-3">
              <div className="bg-gray-50 dark:bg-gray-750 rounded-lg p-3 mb-3 transition-colors border border-gray-100 dark:border-gray-600">
                <div className="flex items-center space-x-3">
                  <div className="relative">
                    <img
                      src={user.avatar || "/placeholder.svg"}
                      alt={user.name}
                      className="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600"
                    />
                    {/* Subtle Premium Indicator */}
                    {user.plan === "Premium" && (
                      <div className="absolute -top-0.5 -right-0.5 w-3 h-3 bg-gray-400 dark:bg-gray-500 rounded-full flex items-center justify-center">
                        <Crown className="w-1.5 h-1.5 text-white" />
                      </div>
                    )}
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-base font-medium text-gray-900 dark:text-gray-100 truncate leading-[1.5]">
                      {user.name}
                    </p>
                    <p className="text-sm text-gray-500 dark:text-gray-400 truncate leading-[1.5]">{user.email}</p>
                    <div className="flex items-center space-x-3 mt-1">
                      <div className="flex items-center space-x-1">
                        <div className="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                        <span className="text-sm text-gray-500 dark:text-gray-400 leading-[1.5]">
                          {user.streak} day streak
                        </span>
                      </div>
                      {user.plan === "Premium" && (
                        <span className="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full font-medium leading-[1.5]">
                          Pro
                        </span>
                      )}
                    </div>
                  </div>
                </div>
              </div>

              <div className="space-y-1">
                <Button
                  variant="ghost"
                  className="w-full justify-start text-base text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#3366CC] dark:hover:text-[#3366CC] leading-[1.5]"
                >
                  <Settings className="mr-3 h-5 w-5" />
                  Settings
                </Button>
                <Button
                  variant="ghost"
                  className="w-full justify-start text-base text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 leading-[1.5]"
                >
                  <LogOut className="mr-3 h-5 w-5" />
                  Sign Out
                </Button>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Main Content */}
      <div className="lg:pl-64">
        <div className="flex flex-col">
          {/* Desktop Header */}
          <div className="hidden lg:block bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 px-6 py-4 transition-colors">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-2xl lg:text-[32px] font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
                  Dashboard
                </h2>
                <p className="text-base text-gray-600 dark:text-gray-400 mt-1 leading-[1.5]">
                  Track your Bible reading progress
                </p>
              </div>
              <div className="flex items-center space-x-3">
                <ThemeToggle />
                <Button
                  onClick={onLogReading}
                  className="bg-[#3366CC] hover:bg-[#2952A3] text-white px-6 py-2 rounded-lg flex items-center space-x-2 text-base leading-[1.5]"
                >
                  <Plus className="w-4 h-4" />
                  <span>Log Reading</span>
                </Button>
              </div>
            </div>
          </div>

          {/* Page Content */}
          <main className="flex-1 p-4 lg:p-6">{children}</main>
        </div>
      </div>

      {/* Mobile FAB */}
      <div className="lg:hidden fixed bottom-20 right-4 z-50">
        <Button
          onClick={onLogReading}
          size="icon"
          className="w-14 h-14 bg-[#3366CC] hover:bg-[#2952A3] text-white rounded-full shadow-lg"
        >
          <Plus className="w-6 h-6" />
        </Button>
      </div>

      {/* Mobile Bottom Navigation */}
      <div className="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-[#D1D7E0] dark:border-gray-700 transition-colors">
        <nav className="flex">
          {navigationItems.slice(0, 3).map((item) => (
            <a
              key={item.label}
              href={item.href}
              className={`flex-1 flex flex-col items-center py-2 px-1 text-sm transition-colors leading-[1.5] ${
                item.active ? "text-[#3366CC]" : "text-gray-500 dark:text-gray-400 hover:text-[#3366CC]"
              }`}
            >
              <item.icon className="w-6 h-6 mb-1" />
              {item.label}
            </a>
          ))}
        </nav>
      </div>
    </div>
  )
}
