# Google Classroom Clone

This is a modern clone of Google Classroom built with **Next.js** and **Supabase**.

## Features

- **Authentication**: Email/Password signup and login.
- **Dashboard**: View all enrolled classes.
- **Class Pages**: Stream, Classwork, People.
- **Assignments**: Create and submit assignments.
- **Realtime**: Live updates for assignments and grades.

## Tech Stack

- **Framework**: Next.js (App Router)
- **Database & Auth**: Supabase
- **Styling**: Tailwind CSS

## Prerequisites

- Node.js (18.x or higher)
- A Supabase account

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd google-classroom-clone
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Set up environment variables:
   Create a `.env.local` file in the root directory:
   ```env
   NEXT_PUBLIC_SUPABASE_URL=your_supabase_url
   NEXT_PUBLIC_SUPABASE_ANON_KEY=your_supabase_anon_key
   ```

## Database Setup

Run the migration scripts to set up the necessary database tables:

```bash
npx supabase db reset --local
```

## Running the App

Start the development server:

```bash
npm run dev
```

Open [http://localhost:3000](http://localhost:3000) in your browser to view the app.