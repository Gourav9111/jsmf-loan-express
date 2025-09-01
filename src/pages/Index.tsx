import Header from "@/components/Header";
import Hero from "@/components/Hero";
import LoanServices from "@/components/LoanServices";
import EMICalculator from "@/components/EMICalculator";
import Footer from "@/components/Footer";

const Index = () => {
  return (
    <div className="min-h-screen">
      <Header />
      <main>
        <Hero />
        <LoanServices />
        <EMICalculator />
      </main>
      <Footer />
    </div>
  );
};

export default Index;
