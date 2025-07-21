"use client"

import type React from "react"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { X, Check } from "lucide-react"

interface LogReadingModalProps {
  isOpen: boolean
  onClose: () => void
}

const bibleBooks = [
  "Genesis",
  "Exodus",
  "Leviticus",
  "Numbers",
  "Deuteronomy",
  "Joshua",
  "Judges",
  "Ruth",
  "1 Samuel",
  "2 Samuel",
  "1 Kings",
  "2 Kings",
  "1 Chronicles",
  "2 Chronicles",
  "Ezra",
  "Nehemiah",
  "Esther",
  "Job",
  "Psalms",
  "Proverbs",
  "Ecclesiastes",
  "Song of Songs",
  "Isaiah",
  "Jeremiah",
  "Lamentations",
  "Ezekiel",
  "Daniel",
  "Hosea",
  "Joel",
  "Amos",
  "Obadiah",
  "Jonah",
  "Micah",
  "Nahum",
  "Habakkuk",
  "Zephaniah",
  "Haggai",
  "Zechariah",
  "Malachi",
  "Matthew",
  "Mark",
  "Luke",
  "John",
  "Acts",
  "Romans",
  "1 Corinthians",
  "2 Corinthians",
  "Galatians",
  "Ephesians",
  "Philippians",
  "Colossians",
  "1 Thessalonians",
  "2 Thessalonians",
  "1 Timothy",
  "2 Timothy",
  "Titus",
  "Philemon",
  "Hebrews",
  "James",
  "1 Peter",
  "2 Peter",
  "1 John",
  "2 John",
  "3 John",
  "Jude",
  "Revelation",
]

const bookChapters: { [key: string]: number } = {
  Genesis: 50,
  Exodus: 40,
  Leviticus: 27,
  Numbers: 36,
  Deuteronomy: 34,
  Joshua: 24,
  Judges: 21,
  Ruth: 4,
  "1 Samuel": 31,
  "2 Samuel": 24,
  "1 Kings": 22,
  "2 Kings": 25,
  "1 Chronicles": 29,
  "2 Chronicles": 36,
  Ezra: 10,
  Nehemiah: 13,
  Esther: 10,
  Job: 42,
  Psalms: 150,
  Proverbs: 31,
  Ecclesiastes: 12,
  "Song of Songs": 8,
  Isaiah: 66,
  Jeremiah: 52,
  Lamentations: 5,
  Ezekiel: 48,
  Daniel: 12,
  Hosea: 14,
  Joel: 3,
  Amos: 9,
  Obadiah: 1,
  Jonah: 4,
  Micah: 7,
  Nahum: 3,
  Habakkuk: 3,
  Zephaniah: 3,
  Haggai: 2,
  Zechariah: 14,
  Malachi: 4,
  Matthew: 28,
  Mark: 16,
  Luke: 24,
  John: 21,
  Acts: 28,
  Romans: 16,
  "1 Corinthians": 16,
  "2 Corinthians": 13,
  Galatians: 6,
  Ephesians: 6,
  Philippians: 4,
  Colossians: 4,
  "1 Thessalonians": 5,
  "2 Thessalonians": 3,
  "1 Timothy": 6,
  "2 Timothy": 4,
  Titus: 3,
  Philemon: 1,
  Hebrews: 13,
  James: 5,
  "1 Peter": 5,
  "2 Peter": 3,
  "1 John": 5,
  "2 John": 1,
  "3 John": 1,
  Jude: 1,
  Revelation: 22,
}

export function LogReadingModal({ isOpen, onClose }: LogReadingModalProps) {
  const [selectedBook, setSelectedBook] = useState("")
  const [selectedChapter, setSelectedChapter] = useState("")
  const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split("T")[0])
  const [notes, setNotes] = useState("")
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [showSuccess, setShowSuccess] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsSubmitting(true)

    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1000))

    setIsSubmitting(false)
    setShowSuccess(true)

    // Reset form and close modal after success
    setTimeout(() => {
      setShowSuccess(false)
      setSelectedBook("")
      setSelectedChapter("")
      setNotes("")
      onClose()
    }, 2000)
  }

  const getChapterOptions = () => {
    if (!selectedBook) return []
    const maxChapters = bookChapters[selectedBook] || 1
    return Array.from({ length: maxChapters }, (_, i) => i + 1)
  }

  if (!isOpen) return null

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <Card className="w-full max-w-md bg-white">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
          <CardTitle className="text-lg font-semibold text-[#4A5568]">Log Bible Reading</CardTitle>
          <Button variant="ghost" size="icon" onClick={onClose} className="h-8 w-8">
            <X className="h-4 w-4" />
          </Button>
        </CardHeader>
        <CardContent>
          {showSuccess ? (
            <div className="text-center py-8">
              <div className="w-16 h-16 bg-[#66CC99] rounded-full flex items-center justify-center mx-auto mb-4">
                <Check className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-lg font-semibold text-[#4A5568] mb-2">Reading Logged!</h3>
              <p className="text-gray-600">Your Bible reading has been successfully recorded.</p>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="date" className="text-sm font-medium text-[#4A5568]">
                  Date
                </Label>
                <Input
                  id="date"
                  type="date"
                  value={selectedDate}
                  onChange={(e) => setSelectedDate(e.target.value)}
                  className="mt-1"
                  required
                />
              </div>

              <div>
                <Label htmlFor="book" className="text-sm font-medium text-[#4A5568]">
                  Bible Book
                </Label>
                <select
                  id="book"
                  value={selectedBook}
                  onChange={(e) => {
                    setSelectedBook(e.target.value)
                    setSelectedChapter("")
                  }}
                  className="mt-1 w-full px-3 py-2 border border-[#D1D7E0] rounded-md focus:outline-none focus:ring-2 focus:ring-[#3366CC] focus:border-transparent"
                  required
                >
                  <option value="">Select a book...</option>
                  {bibleBooks.map((book) => (
                    <option key={book} value={book}>
                      {book}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <Label htmlFor="chapter" className="text-sm font-medium text-[#4A5568]">
                  Chapter
                </Label>
                <select
                  id="chapter"
                  value={selectedChapter}
                  onChange={(e) => setSelectedChapter(e.target.value)}
                  className="mt-1 w-full px-3 py-2 border border-[#D1D7E0] rounded-md focus:outline-none focus:ring-2 focus:ring-[#3366CC] focus:border-transparent"
                  disabled={!selectedBook}
                  required
                >
                  <option value="">Select a chapter...</option>
                  {getChapterOptions().map((chapter) => (
                    <option key={chapter} value={chapter}>
                      Chapter {chapter}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <Label htmlFor="notes" className="text-sm font-medium text-[#4A5568]">
                  Notes (Optional)
                </Label>
                <Textarea
                  id="notes"
                  value={notes}
                  onChange={(e) => setNotes(e.target.value)}
                  placeholder="Add any thoughts or reflections..."
                  className="mt-1 resize-none"
                  rows={3}
                  maxLength={500}
                />
                <div className="text-xs text-gray-500 mt-1">{notes.length}/500 characters</div>
              </div>

              <div className="flex space-x-3 pt-4">
                <Button type="button" variant="outline" onClick={onClose} className="flex-1" disabled={isSubmitting}>
                  Cancel
                </Button>
                <Button
                  type="submit"
                  className="flex-1 bg-[#3366CC] hover:bg-[#2952A3] text-white"
                  disabled={isSubmitting}
                >
                  {isSubmitting ? "Saving..." : "Save Reading"}
                </Button>
              </div>
            </form>
          )}
        </CardContent>
      </Card>
    </div>
  )
}
