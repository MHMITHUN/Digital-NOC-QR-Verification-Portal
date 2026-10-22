/**
 * Serverless API: /api/verify
 * Verification Handler for Vercel / Netlify
 * Author: MD. Mahamudul Hasan
 */

import { getDatabase, initialDocuments } from './db.js';

export default async function handler(req, res) {
  // CORS Headers
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }

  const query = (req.query.doc || req.query.hash || (req.body && (req.body.doc || req.body.hash)) || '').trim().toLowerCase();

  if (!query) {
    return res.status(400).json({
      status: 'error',
      message: 'Document number (doc) or QR hash (hash) parameter is required.'
    });
  }

  try {
    const db = await getDatabase();
    let doc = null;

    if (db) {
      const collection = db.collection('noc_certificates');
      
      // Auto-seed if empty
      const count = await collection.countDocuments();
      if (count === 0) {
        await collection.insertMany(initialDocuments);
      }

      doc = await collection.findOne({
        $or: [
          { docNo: { $regex: new RegExp(`^${query}$`, 'i') } },
          { hash: { $regex: new RegExp(query, 'i') } }
        ]
      });

      // Log verification attempt
      const logs = db.collection('verification_logs');
      await logs.insertOne({
        query,
        result: doc ? 'Verified Authentic' : 'Not Found',
        timestamp: new Date(),
        ip: req.headers['x-forwarded-for'] || req.socket.remoteAddress || 'unknown'
      });
    } else {
      // In-memory fallback
      doc = initialDocuments.find(d => 
        d.docNo.toLowerCase() === query || 
        d.hash.toLowerCase().includes(query)
      );
    }

    if (doc) {
      return res.status(200).json({
        status: 'success',
        verified: true,
        result: 'AUTHENTIC & ACTIVE',
        data: doc,
        verified_at: new Date().toISOString(),
        gateway: 'BSREA Vercel Serverless Security Engine'
      });
    } else {
      return res.status(404).json({
        status: 'not_found',
        verified: false,
        message: `No authentic BSREA document was found matching "${query}".`
      });
    }
  } catch (error) {
    console.error('Verification error:', error);
    return res.status(500).json({
      status: 'error',
      message: 'Server error processing verification request: ' + error.message
    });
  }
}
