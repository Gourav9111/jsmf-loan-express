import { useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Slider } from "@/components/ui/slider";
import { Calculator, IndianRupee, Calendar, Percent } from "lucide-react";

const EMICalculator = () => {
  const [loanAmount, setLoanAmount] = useState([2500000]);
  const [interestRate, setInterestRate] = useState([8.5]);
  const [loanTenure, setLoanTenure] = useState([20]);

  const calculateEMI = () => {
    const principal = loanAmount[0];
    const rate = interestRate[0] / 12 / 100;
    const tenure = loanTenure[0] * 12;

    const emi = (principal * rate * Math.pow(1 + rate, tenure)) / (Math.pow(1 + rate, tenure) - 1);
    const totalAmount = emi * tenure;
    const totalInterest = totalAmount - principal;

    return {
      emi: Math.round(emi),
      totalAmount: Math.round(totalAmount),
      totalInterest: Math.round(totalInterest)
    };
  };

  const { emi, totalAmount, totalInterest } = calculateEMI();

  const formatIndianCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: 'INR',
      maximumFractionDigits: 0
    }).format(amount);
  };

  return (
    <section id="calculator" className="py-20 bg-background">
      <div className="container mx-auto px-4">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold mb-4">EMI Calculator</h2>
          <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
            Calculate your monthly EMI and plan your finances better
          </p>
        </div>

        <div className="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
          {/* Calculator Inputs */}
          <Card className="shadow-card">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Calculator className="h-5 w-5 text-primary" />
                Loan Details
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-8">
              {/* Loan Amount */}
              <div className="space-y-4">
                <Label className="text-base font-semibold flex items-center gap-2">
                  <IndianRupee className="h-4 w-4" />
                  Loan Amount: {formatIndianCurrency(loanAmount[0])}
                </Label>
                <Slider
                  value={loanAmount}
                  onValueChange={setLoanAmount}
                  max={50000000}
                  min={100000}
                  step={100000}
                  className="w-full"
                />
                <div className="flex justify-between text-sm text-muted-foreground">
                  <span>₹1L</span>
                  <span>₹5Cr</span>
                </div>
              </div>

              {/* Interest Rate */}
              <div className="space-y-4">
                <Label className="text-base font-semibold flex items-center gap-2">
                  <Percent className="h-4 w-4" />
                  Interest Rate: {interestRate[0]}% per annum
                </Label>
                <Slider
                  value={interestRate}
                  onValueChange={setInterestRate}
                  max={24}
                  min={7}
                  step={0.1}
                  className="w-full"
                />
                <div className="flex justify-between text-sm text-muted-foreground">
                  <span>7%</span>
                  <span>24%</span>
                </div>
              </div>

              {/* Loan Tenure */}
              <div className="space-y-4">
                <Label className="text-base font-semibold flex items-center gap-2">
                  <Calendar className="h-4 w-4" />
                  Loan Tenure: {loanTenure[0]} years
                </Label>
                <Slider
                  value={loanTenure}
                  onValueChange={setLoanTenure}
                  max={30}
                  min={1}
                  step={1}
                  className="w-full"
                />
                <div className="flex justify-between text-sm text-muted-foreground">
                  <span>1 Year</span>
                  <span>30 Years</span>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Results */}
          <Card className="shadow-card bg-gradient-secondary">
            <CardHeader>
              <CardTitle>EMI Calculation Results</CardTitle>
            </CardHeader>
            <CardContent className="space-y-6">
              {/* Monthly EMI */}
              <div className="bg-gradient-primary text-primary-foreground p-6 rounded-lg text-center">
                <div className="text-sm opacity-90 mb-2">Monthly EMI</div>
                <div className="text-3xl font-bold">{formatIndianCurrency(emi)}</div>
              </div>

              {/* Breakdown */}
              <div className="grid grid-cols-2 gap-4">
                <div className="bg-background p-4 rounded-lg text-center border">
                  <div className="text-sm text-muted-foreground mb-2">Total Interest</div>
                  <div className="text-xl font-bold text-destructive">
                    {formatIndianCurrency(totalInterest)}
                  </div>
                </div>
                <div className="bg-background p-4 rounded-lg text-center border">
                  <div className="text-sm text-muted-foreground mb-2">Total Amount</div>
                  <div className="text-xl font-bold text-primary">
                    {formatIndianCurrency(totalAmount)}
                  </div>
                </div>
              </div>

              {/* Pie Chart Representation */}
              <div className="space-y-3">
                <div className="text-sm font-semibold">Loan Breakdown</div>
                <div className="space-y-2">
                  <div className="flex items-center gap-3">
                    <div className="w-4 h-4 bg-primary rounded"></div>
                    <span className="text-sm">Principal: {formatIndianCurrency(loanAmount[0])}</span>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-4 h-4 bg-destructive rounded"></div>
                    <span className="text-sm">Interest: {formatIndianCurrency(totalInterest)}</span>
                  </div>
                </div>
              </div>

              <Button variant="cta" className="w-full">
                Apply for This Loan
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </section>
  );
};

export default EMICalculator;