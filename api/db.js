/**
 * MongoDB Connection Handler for Vercel / Netlify Serverless Functions
 * BSREA Digital NOC & QR Verification Portal
 */

import { MongoClient } from 'mongodb';

const uri = process.env.MONGODB_URI || '';
let client = null;
let clientPromise = null;

// Initial Seed Documents if MongoDB collection is empty
export const initialDocuments = [
  {
    docNo: 'NOC-2026-SOLAR-084',
    title: 'Commercial 5MW Solar PV Grid Interconnection Authorization NOC',
    applicant: 'Rahimafrooz Renewable Energy Ltd.',
    sector: 'Solar Energy',
    representative: 'Engr. M. Rahman',
    issueDate: '2026-10-12',
    validUntil: '2027-10-11',
    authority: 'BSREA Secretariat, Gulshan-2, Dhaka',
    hash: 'e89c31fa780d6b2c45e12aa4f8b910e5893a201bfd90a1bc34e56f7890123abc',
    status: 'AUTHENTIC & ACTIVE',
    type: 'NOC - Grid Interconnection'
  },
  {
    docNo: 'CERT-BSREA-MEM-129',
    title: 'Certified Renewable Equipment Manufacturer & Solar EPC Provider',
    applicant: 'Solaric Bangladesh Limited',
    sector: 'Solar Energy',
    representative: 'Tanvir Hossain',
    issueDate: '2026-08-05',
    validUntil: '2027-12-31',
    authority: 'BSREA Secretariat, Gulshan-2, Dhaka',
    hash: '4a1b9c77e201f654aa39c09d84fbb128562cd99a01234ef987654321fedcba01',
    status: 'AUTHENTIC & ACTIVE',
    type: 'Membership Certificate'
  },
  {
    docNo: 'NOC-2026-WIND-031',
    title: 'Wind Resource Assessment & Meteorological Mast Permit NOC',
    applicant: 'Green Energy Engineering BD',
    sector: 'Wind Energy',
    representative: 'Engr. Kamal Uddin',
    issueDate: '2026-09-18',
    validUntil: '2027-09-17',
    authority: 'BSREA Secretariat, Gulshan-2, Dhaka',
    hash: '9f2c81da540e1189ac3401fe7809a23bc110a99c87123ef6543210fedcba9876',
    status: 'AUTHENTIC & ACTIVE',
    type: 'NOC - Assessment Permit'
  }
];

export async function getDatabase() {
  if (!uri) {
    console.warn('MONGODB_URI not provided. Using in-memory fallback.');
    return null;
  }

  if (!clientPromise) {
    client = new MongoClient(uri, {
      maxPoolSize: 10,
      serverSelectionTimeoutMS: 5000,
    });
    clientPromise = client.connect();
  }

  const connectedClient = await clientPromise;
  return connectedClient.db('bsrea_portal');
}
