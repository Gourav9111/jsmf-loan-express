import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { 
  Home, 
  Car, 
  CreditCard, 
  Building2, 
  Landmark,
  GraduationCap,
  Coins,
  TrendingUp
} from "lucide-react";

// Import loan images
import homeLoanImg from "@/assets/home-loan.jpg";
import personalLoanImg from "@/assets/personal-loan.jpg";
import businessLoanImg from "@/assets/business-loan.jpg";
import carLoanImg from "@/assets/car-loan.jpg";
import propertyLoanImg from "@/assets/property-loan.jpg";
import educationLoanImg from "@/assets/education-loan.jpg";
import goldLoanImg from "@/assets/gold-loan.jpg";
import topUpLoanImg from "@/assets/top-up-loan.jpg";

const LoanServices = () => {
  const loanTypes = [
    {
      icon: Home,
      title: "Home Loans",
      description: "Starting from 7% interest rate",
      features: ["Salaried & Self-Employed", "Balance Transfer", "Construction Loan"],
      amount: "₹10L - ₹5Cr",
      color: "text-blue-600",
      image: homeLoanImg
    },
    {
      icon: CreditCard,
      title: "Personal Loans",
      description: "Quick approval in 24 hours",
      features: ["Medical Emergency", "Marriage/Events", "Education"],
      amount: "₹1L - ₹40L",
      color: "text-green-600",
      image: personalLoanImg
    },
    {
      icon: Building2,
      title: "Business Loans",
      description: "Fuel your business growth",
      features: ["SME/MSME", "Startup Loan", "Working Capital"],
      amount: "₹5L - ₹10Cr",
      color: "text-purple-600",
      image: businessLoanImg
    },
    {
      icon: Car,
      title: "Car Loans",
      description: "Drive your dream car today",
      features: ["New Car", "Used Car", "Balance Transfer"],
      amount: "₹2L - ₹1Cr",
      color: "text-orange-600",
      image: carLoanImg
    },
    {
      icon: Landmark,
      title: "Loan Against Property",
      description: "Unlock property value",
      features: ["Residential", "Commercial", "Industrial"],
      amount: "₹10L - ₹20Cr",
      color: "text-red-600",
      image: propertyLoanImg
    },
    {
      icon: GraduationCap,
      title: "Education Loans",
      description: "Invest in your future",
      features: ["India Studies", "Abroad Studies", "Professional Courses"],
      amount: "₹1L - ₹1.5Cr",
      color: "text-indigo-600",
      image: educationLoanImg
    },
    {
      icon: Coins,
      title: "Gold Loans",
      description: "Instant loan against gold",
      features: ["Instant Approval", "Low Interest", "Quick Disbursal"],
      amount: "₹5K - ₹1Cr",
      color: "text-yellow-600",
      image: goldLoanImg
    },
    {
      icon: TrendingUp,
      title: "Top-Up Loans",
      description: "Additional funding made easy",
      features: ["Home Loan Top-Up", "Personal Top-Up", "Business Top-Up"],
      amount: "₹1L - ₹50L",
      color: "text-teal-600",
      image: topUpLoanImg
    }
  ];

  return (
    <section id="services" className="py-20 bg-muted/30">
      <div className="container mx-auto px-4">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold mb-4">Our Loan Services</h2>
          <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
            Comprehensive financial solutions tailored to meet all your needs
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          {loanTypes.map((loan, index) => {
            const Icon = loan.icon;
            return (
              <Card key={index} className="group hover:shadow-elevated transition-smooth cursor-pointer overflow-hidden">
                {/* Image Header */}
                <div className="relative h-48 overflow-hidden">
                  <img 
                    src={loan.image} 
                    alt={loan.title}
                    className="w-full h-full object-cover group-hover:scale-110 transition-smooth duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                  <div className="absolute bottom-4 left-4">
                    <div className="w-12 h-12 bg-primary rounded-full flex items-center justify-center group-hover:scale-110 transition-bounce">
                      <Icon className="h-6 w-6 text-primary-foreground" />
                    </div>
                  </div>
                </div>

                <CardHeader className="pb-4">
                  <CardTitle className="text-xl">{loan.title}</CardTitle>
                  <p className="text-sm text-muted-foreground">{loan.description}</p>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div className="space-y-2">
                    {loan.features.map((feature, idx) => (
                      <div key={idx} className="flex items-center gap-2 text-sm">
                        <div className="w-1.5 h-1.5 bg-primary rounded-full"></div>
                        <span>{feature}</span>
                      </div>
                    ))}
                  </div>
                  
                  <div className="border-t pt-4">
                    <div className="text-sm text-muted-foreground mb-2">Loan Amount</div>
                    <div className="font-bold text-primary text-lg">{loan.amount}</div>
                  </div>

                  <Button variant="outline" className="w-full group-hover:bg-primary group-hover:text-primary-foreground transition-smooth">
                    Learn More
                  </Button>
                </CardContent>
              </Card>
            );
          })}
        </div>

        <div className="text-center mt-12">
          <Button variant="hero" size="xl">
            View All Loan Services
          </Button>
        </div>
      </div>
    </section>
  );
};

export default LoanServices;