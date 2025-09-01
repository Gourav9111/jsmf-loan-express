import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";
import { Menu, Phone, Mail } from "lucide-react";

const Header = () => {
  const [isOpen, setIsOpen] = useState(false);

  const navigationItems = [
    { name: "Home", href: "#home" },
    { name: "About", href: "#about" },
    { name: "Loan Services", href: "#services" },
    { name: "EMI Calculator", href: "#calculator" },
    { name: "Contact", href: "#contact" },
  ];

  return (
    <header className="sticky top-0 z-50 w-full bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 border-b">
      <div className="container mx-auto px-4">
        {/* Top Bar */}
        <div className="flex items-center justify-between py-2 text-sm border-b border-border/50">
          <div className="flex items-center gap-6">
            <div className="flex items-center gap-2">
              <Phone className="h-4 w-4 text-primary" />
              <span>+91 62620 79180</span>
            </div>
            <div className="hidden md:flex items-center gap-2">
              <Mail className="h-4 w-4 text-primary" />
              <span>costumercare@jsmf.in</span>
            </div>
          </div>
          <div className="text-muted-foreground">
            ✅ 1000+ Trusted Clients | ₹200+ Crore Disbursed
          </div>
        </div>

        {/* Main Navigation */}
        <div className="flex items-center justify-between py-4">
          <div className="flex items-center">
            <img 
              src="/lovable-uploads/4359f624-fa92-47f1-bfa2-35332b7c1957.png" 
              alt="JSMF Logo" 
              className="h-12 w-auto"
            />
          </div>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            {navigationItems.map((item) => (
              <a
                key={item.name}
                href={item.href}
                className="text-foreground hover:text-primary transition-smooth font-medium"
              >
                {item.name}
              </a>
            ))}
          </nav>

          <div className="flex items-center gap-4">
            <Button variant="outline" size="sm" className="hidden md:flex">
              Check Eligibility
            </Button>
            <Button variant="cta" size="sm">
              Apply Now
            </Button>

            {/* Mobile Menu */}
            <Sheet open={isOpen} onOpenChange={setIsOpen}>
              <SheetTrigger asChild className="md:hidden">
                <Button variant="ghost" size="icon">
                  <Menu className="h-6 w-6" />
                </Button>
              </SheetTrigger>
              <SheetContent side="right" className="w-80">
                <div className="flex flex-col space-y-4 mt-8">
                  {navigationItems.map((item) => (
                    <a
                      key={item.name}
                      href={item.href}
                      className="text-lg font-medium hover:text-primary transition-smooth"
                      onClick={() => setIsOpen(false)}
                    >
                      {item.name}
                    </a>
                  ))}
                  <div className="pt-4 space-y-3">
                    <Button variant="outline" className="w-full">
                      Check Eligibility
                    </Button>
                    <Button variant="cta" className="w-full">
                      Apply Now
                    </Button>
                  </div>
                </div>
              </SheetContent>
            </Sheet>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;