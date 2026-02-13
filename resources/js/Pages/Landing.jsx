import { Head } from "@inertiajs/react";
import GoldParticles from "../Components/GoldParticles";
import Navbar from "../Components/Navbar";
import Hero from "../Components/Hero";
import LeadForm from "../Components/LeadForm";
import Features from "../Components/Features";
import Pricing from "../Components/Pricing";
import Testimonials from "../Components/Testimonials";
import CtaFaq from "../Components/CtaFaq";
import Footer from "../Components/Footer";

export default function Landing() {
    return (
        <>
            <Head title="Aurify — Ubah Keuangan Anda Menjadi Emas" />
            <GoldParticles />
            <div className="relative z-10">
                <Navbar />
                <main>
                    <Hero />
                    <LeadForm />
                    <Features />
                    <Pricing />
                    <Testimonials />
                    <CtaFaq />
                </main>
                <Footer />
            </div>
        </>
    );
}
