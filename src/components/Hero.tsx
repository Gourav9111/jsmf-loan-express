import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { CheckCircle, TrendingUp, Users, MapPin, CreditCard } from "lucide-react";

const Hero = () => {
  const highlights = [
    { icon: Users, text: "1000+ Trusted Clients", color: "text-primary" },
    { icon: MapPin, text: "10+ Locations in India", color: "text-primary" },
    { icon: TrendingUp, text: "₹200+ Crore Disbursed", color: "text-primary" },
    { icon: CreditCard, text: "Top 10 Bank Partners", color: "text-primary" }
  ];

  return (
    <section id="home" className="relative overflow-hidden">
      {/* Hero Background */}
      <div className="bg-gradient-hero text-primary-foreground">
        <div className="container mx-auto px-4 py-20">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            {/* Hero Content */}
            <div className="space-y-8">
              <div className="space-y-4">
                <h1 className="text-4xl md:text-6xl font-bold leading-tight">
                  Your Trusted 
                  <span className="block text-primary-light">Loan Partner</span>
                </h1>
                <p className="text-xl text-primary-foreground/90 max-w-lg">
                  Serving 1000+ Clients Across India with Quick, Hassle-Free Loan Solutions
                </p>
              </div>

              <div className="flex flex-col sm:flex-row gap-4">
                <Button variant="secondary" size="xl" className="font-bold">
                  Apply Now
                </Button>
                <Button variant="outline" size="xl" className="border-white text-white hover:bg-white hover:text-primary">
                  Check Eligibility
                </Button>
              </div>

              {/* Key Highlights */}
              <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 pt-8">
                {highlights.map((highlight, index) => {
                  const Icon = highlight.icon;
                  return (
                    <div key={index} className="flex items-center gap-2">
                      <CheckCircle className="h-5 w-5 text-primary-light flex-shrink-0" />
                      <span className="text-sm font-medium">{highlight.text}</span>
                    </div>
                  );
                })}
              </div>
            </div>

            {/* Hero Image/Stats */}
            <div className="relative">
              <Card className="bg-background/10 backdrop-blur border-white/20">
                <CardContent className="p-8">
                  <div className="grid grid-cols-2 gap-6 text-center">
                    <div>
                      <div className="text-3xl font-bold text-white">1000+</div>
                      <div className="text-primary-foreground/80">Happy Clients</div>
                    </div>
                    <div>
                      <div className="text-3xl font-bold text-white">₹200Cr+</div>
                      <div className="text-primary-foreground/80">Loans Disbursed</div>
                    </div>
                    <div>
                      <div className="text-3xl font-bold text-white">10+</div>
                      <div className="text-primary-foreground/80">Cities Covered</div>
                    </div>
                    <div>
                      <div className="text-3xl font-bold text-white">7%</div>
                      <div className="text-primary-foreground/80">Starting Rate</div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </div>

      {/* Trust Indicators */}
      <div className="bg-accent py-8">
        <div className="container mx-auto px-4">
          <div className="text-center mb-8">
            <h3 className="text-2xl font-bold text-accent-foreground">
              Partnered with India's Leading Banks
            </h3>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-5 gap-8 items-center opacity-70">
            {["SBI", "HDFC", "ICICI", "AXIS", "KOTAK"].map((bank) => (
              <div key={bank} className="text-center">
                <div className="h-12 bg-muted rounded-lg flex items-center justify-center">
                  <span className="font-bold text-muted-foreground">{bank}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;