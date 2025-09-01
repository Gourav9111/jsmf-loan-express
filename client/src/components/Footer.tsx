import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Separator } from "@/components/ui/separator";
import { 
  MapPin, 
  Phone, 
  Mail, 
  Facebook, 
  Twitter, 
  Linkedin, 
  Instagram,
  Home,
  CreditCard,
  Building2,
  Car
} from "lucide-react";

const Footer = () => {
  const quickLinks = [
    { name: "Home Loans", href: "#" },
    { name: "Personal Loans", href: "#" },
    { name: "Business Loans", href: "#" },
    { name: "Car Loans", href: "#" },
    { name: "Education Loans", href: "#" },
    { name: "Gold Loans", href: "#" }
  ];

  const companyLinks = [
    { name: "About Us", href: "#about" },
    { name: "Our Team", href: "#" },
    { name: "Careers", href: "#" },
    { name: "Contact Us", href: "#contact" },
    { name: "Branch Locator", href: "#" }
  ];

  const legalLinks = [
    { name: "Privacy Policy", href: "#" },
    { name: "Terms & Conditions", href: "#" },
    { name: "Disclaimer", href: "#" },
    { name: "RBI Guidelines", href: "#" },
    { name: "Grievance Redressal", href: "#" }
  ];

  return (
    <footer className="bg-primary text-primary-foreground">
      <div className="container mx-auto px-4">
        {/* Main Footer Content */}
        <div className="py-16">
          <div className="grid lg:grid-cols-4 md:grid-cols-2 gap-8">
            {/* Company Info */}
            <div className="space-y-6">
              <div>
                <img 
                  src="/lovable-uploads/4359f624-fa92-47f1-bfa2-35332b7c1957.png" 
                  alt="JSMF Logo" 
                  className="h-12 w-auto mb-4 brightness-0 invert"
                />
                <p className="text-primary-foreground/80 leading-relaxed">
                  Your trusted financial partner serving 1000+ clients across India with quick, 
                  hassle-free loan solutions.
                </p>
              </div>
              
              <div className="space-y-3">
                <div className="flex items-start gap-3">
                  <MapPin className="h-5 w-5 mt-1 text-primary-light flex-shrink-0" />
                  <div className="text-sm">
                    <div className="font-semibold">HARSH SAHU</div>
                    <div>Shop No 2, Near Mittal College,</div>
                    <div>Karond, Bhopal, Madhya Pradesh</div>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <Phone className="h-5 w-5 text-primary-light" />
                  <span className="text-sm">+91 62620 79180</span>
                </div>
                <div className="flex items-center gap-3">
                  <Mail className="h-5 w-5 text-primary-light" />
                  <span className="text-sm">costumercare@jsmf.in</span>
                </div>
              </div>
            </div>

            {/* Quick Links */}
            <div>
              <h3 className="text-lg font-bold mb-6">Loan Services</h3>
              <ul className="space-y-3">
                {quickLinks.map((link) => (
                  <li key={link.name}>
                    <a 
                      href={link.href} 
                      className="text-primary-foreground/80 hover:text-primary-light transition-smooth text-sm"
                    >
                      {link.name}
                    </a>
                  </li>
                ))}
              </ul>
            </div>

            {/* Company Links */}
            <div>
              <h3 className="text-lg font-bold mb-6">Company</h3>
              <ul className="space-y-3">
                {companyLinks.map((link) => (
                  <li key={link.name}>
                    <a 
                      href={link.href} 
                      className="text-primary-foreground/80 hover:text-primary-light transition-smooth text-sm"
                    >
                      {link.name}
                    </a>
                  </li>
                ))}
              </ul>
            </div>

            {/* Newsletter & Social */}
            <div>
              <h3 className="text-lg font-bold mb-6">Stay Connected</h3>
              <div className="space-y-4">
                <p className="text-sm text-primary-foreground/80">
                  Subscribe to get updates on latest loan offers and financial tips.
                </p>
                <div className="flex gap-2">
                  <Input 
                    placeholder="Enter your email" 
                    className="bg-primary-foreground/10 border-primary-foreground/20 text-primary-foreground placeholder:text-primary-foreground/60"
                  />
                  <Button variant="secondary" size="sm">
                    Subscribe
                  </Button>
                </div>
                
                <div className="pt-4">
                  <div className="text-sm font-semibold mb-3">Follow Us</div>
                  <div className="flex gap-3">
                    {[Facebook, Twitter, Linkedin, Instagram].map((Icon, index) => (
                      <a
                        key={index}
                        href="#"
                        className="w-8 h-8 bg-primary-foreground/10 rounded-full flex items-center justify-center hover:bg-primary-light transition-smooth"
                      >
                        <Icon className="h-4 w-4" />
                      </a>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <Separator className="bg-primary-foreground/20" />

        {/* Partner Banks */}
        <div className="py-8">
          <div className="text-center mb-6">
            <h4 className="font-semibold text-primary-foreground/80">Our Banking Partners</h4>
          </div>
          <div className="grid grid-cols-5 md:grid-cols-10 gap-4 items-center">
            {[
              "SBI", "HDFC", "ICICI", "AXIS", "KOTAK", 
              "PNB", "BoB", "IDFC", "UNION", "CANARA"
            ].map((bank) => (
              <div key={bank} className="text-center">
                <div className="h-8 bg-primary-foreground/10 rounded flex items-center justify-center">
                  <span className="text-xs font-medium text-primary-foreground/70">{bank}</span>
                </div>
              </div>
            ))}
          </div>
        </div>

        <Separator className="bg-primary-foreground/20" />

        {/* Legal Links */}
        <div className="py-6">
          <div className="flex flex-wrap justify-center gap-6 mb-4">
            {legalLinks.map((link) => (
              <a
                key={link.name}
                href={link.href}
                className="text-xs text-primary-foreground/60 hover:text-primary-light transition-smooth"
              >
                {link.name}
              </a>
            ))}
          </div>
        </div>

        <Separator className="bg-primary-foreground/20" />

        {/* Copyright */}
        <div className="py-6 text-center">
          <p className="text-sm text-primary-foreground/60">
            © 2024 Jay Shree Mahakal Finance (JSMF). All rights reserved. 
            <span className="block mt-1">
              Licensed financial services provider. Subject to RBI guidelines.
            </span>
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;