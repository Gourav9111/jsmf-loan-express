# Jay Shree Mahakal Finance Services (JSMF) - React/Express Finance Platform

## Overview

This project is a modern finance services website for Jay Shree Mahakal Finance Services (JSMF), built as a full-stack web application. The platform serves customers seeking various loan products including home loans, personal loans, business loans, and car loans. The application features an interactive EMI calculator, loan application system, and professional branding with a red-themed design system. The project is structured as a monorepo with separate client-side React application and Express.js server.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Frontend Architecture
- **Framework**: React 18 with TypeScript for type safety and modern development patterns
- **Styling**: Tailwind CSS with shadcn/ui component library for consistent, professional UI components
- **Design System**: Red-themed branding with CSS custom properties for consistent theming across all components
- **State Management**: TanStack React Query for server state management and caching
- **Routing**: React Router for client-side navigation with catch-all route handling
- **Build Tool**: Vite for fast development and optimized production builds

### Backend Architecture
- **Runtime**: Node.js with Express.js framework for REST API endpoints
- **Language**: TypeScript for type safety across the entire backend
- **Development Setup**: Hot module replacement with Vite integration for seamless full-stack development
- **Route Structure**: Modular route registration system with centralized error handling middleware
- **Storage Interface**: Abstracted storage layer with in-memory implementation for development

### Data Storage Solutions
- **Database**: PostgreSQL configured through Drizzle ORM for type-safe database operations
- **ORM**: Drizzle with Zod integration for schema validation and type inference
- **Connection**: Neon Database serverless PostgreSQL for cloud hosting
- **Migrations**: Drizzle Kit for database schema migrations and management
- **Development Storage**: In-memory storage implementation for local development and testing

### Authentication and Authorization Mechanisms
- **User Schema**: Basic user model with username/password authentication ready for expansion
- **Session Management**: Prepared for session-based authentication with connect-pg-simple for PostgreSQL session storage
- **Type Safety**: Zod schemas for request validation and TypeScript interfaces for type checking

### Component Architecture
- **UI Components**: Comprehensive shadcn/ui component library including forms, navigation, dialogs, and data display
- **Layout Components**: Modular header, footer, and page-specific components for consistent user experience
- **Interactive Features**: EMI calculator with real-time calculations and Indian currency formatting
- **Responsive Design**: Mobile-first approach with Tailwind responsive utilities

## External Dependencies

### Database and Hosting
- **Database Provider**: Neon Database for serverless PostgreSQL hosting
- **Connection Pool**: @neondatabase/serverless for optimized serverless connections
- **Environment Configuration**: Environment-based database URL configuration for different deployment stages

### UI and Styling Libraries
- **Component Library**: Radix UI primitives for accessible, unstyled UI components
- **Icons**: Lucide React for consistent iconography throughout the application
- **Form Handling**: React Hook Form with Hookform Resolvers for form validation
- **Date Utilities**: date-fns for date manipulation and formatting

### Development Tools
- **Build System**: Vite with React plugin and TypeScript support
- **Code Quality**: ESBuild for production bundling and TypeScript checking
- **Development Features**: Replit-specific plugins for cartographer and error overlay in development environment
- **Path Resolution**: Configured path aliases for clean imports (@/, @shared/, @assets/)

### Charts and Visualization
- **Chart Library**: Recharts for EMI calculator visualizations and data presentation
- **Carousel**: Embla Carousel for testimonials and featured content sections

### Business Logic
- **EMI Calculations**: Custom calculation logic for loan EMI, total interest, and payment breakdowns
- **Currency Formatting**: Indian Rupee formatting with proper locale support
- **Form Validation**: Comprehensive validation for loan applications and user inputs