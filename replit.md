# Overview

This is a full-stack React application for JSMF (loan services company) built with Express.js backend and React frontend. The application provides loan services information, EMI calculator functionality, and a professional website interface for a financial services company serving 1000+ clients across India with ₹200+ crore disbursed.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture
- **React 18** with TypeScript for type safety and modern development experience
- **Vite** as the build tool for fast development and optimized production builds
- **TailwindCSS** for utility-first styling with custom design tokens for JSMF branding
- **shadcn/ui** component library providing pre-built, accessible UI components
- **React Router** for client-side routing with a catch-all 404 page
- **TanStack Query** for server state management and API data fetching
- **React Hook Form** with Zod validation for form handling and validation

## Backend Architecture
- **Express.js** server with TypeScript for API routes and middleware
- **Modular route structure** with centralized route registration
- **Custom logging middleware** for API request/response tracking
- **Error handling middleware** for consistent error responses
- **In-memory storage** with interface-based design for easy database migration
- **Development/Production environment** configuration with Vite integration

## Database & ORM
- **Drizzle ORM** with PostgreSQL dialect for type-safe database operations
- **Neon Database** serverless PostgreSQL for cloud-hosted database
- **Schema-first approach** with shared types between frontend and backend
- **Migration system** using Drizzle Kit for database schema management
- **Zod integration** for runtime validation of database schemas

## Styling & Design System
- **Custom JSMF brand colors** with red primary theme (HSL color system)
- **CSS custom properties** for consistent theming across components
- **Professional gradients and shadows** for modern visual appeal
- **Responsive design** with mobile-first approach
- **Accessibility-focused** components from Radix UI primitives

## Development Tools
- **TypeScript** throughout the stack for type safety
- **ESBuild** for production server bundling
- **PostCSS** with Autoprefixer for CSS processing
- **Path aliases** for clean imports (@/, @shared/, @assets/)
- **Development server** with HMR and error overlay

## External Dependencies

### UI & Styling
- **@radix-ui/react-*** - Accessible, unstyled UI primitives for complex components
- **tailwindcss** - Utility-first CSS framework
- **class-variance-authority** - Component variant management
- **clsx** - Conditional className utility

### Database & ORM
- **@neondatabase/serverless** - Serverless PostgreSQL driver for Neon
- **drizzle-orm** - Type-safe ORM with excellent TypeScript support
- **drizzle-zod** - Integration between Drizzle and Zod for validation

### Forms & Validation
- **react-hook-form** - Performant forms with easy validation
- **@hookform/resolvers** - Validation library integrations
- **zod** - TypeScript-first schema validation

### State Management & API
- **@tanstack/react-query** - Server state management and caching
- **React Router** - Declarative routing for React applications

### Development & Build
- **vite** - Fast build tool and development server
- **tsx** - TypeScript execution environment for Node.js
- **esbuild** - Fast JavaScript bundler for production builds

### Session & Storage
- **connect-pg-simple** - PostgreSQL session store for Express sessions
- **express-session** - Session middleware for Express

### Utilities
- **date-fns** - Modern JavaScript date utility library
- **nanoid** - URL-safe unique string ID generator
- **cmdk** - Command palette component
- **embla-carousel-react** - Carousel component for React