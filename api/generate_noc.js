/**
 * Serverless API: /api/generate_noc
 * Issue & Register New Digital NOC / Certificate
 * Author: MD. Mahamudul Hasan
 */

import crypto from 'crypto';
import { getDatabase, initialDocuments } from './db.js';

export default async function handler(req, res) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }

  if (req.method !== 'POST') {
    return res.status(405).json({ status: 'error', message: 'Method Not Allowed. Use POST.' });
  }

  const { applicant, sector, representative, purpose, docType } = req.body || {};

  if (!applicant || !representative || !purpose) {
    return res.status(422).json({
      status: 'error',
      message: 'Applicant organization, representative, and purpose are required.'
    });
  }

  const randId = Math.floor(100 + Math.random() * 900);
  const sectorPrefix = (sector || 'SOLAR').split(' ')[0].toUpperCase();
  const docNo = `NOC-2026-${sectorPrefix}-${randId}`;
  const issueDate = new Date().toISOString().split('T')[0];
  const nextYear = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

  // Generate SHA-256 Digest
  const hashDigest = crypto
    .createHash('sha256')
    .update(`${docNo}|${applicant}|${issueDate}|BSREA_SALT_2026`)
    .digest('hex');

  const newDoc = {
    docNo,
    title: purpose,
    applicant,
    sector: sector || 'Solar Energy',
    representative,
    issueDate,
    validUntil: nextYear,
    authority: 'BSREA Secretariat, Gulshan-2, Dhaka',
    hash: hashDigest,
    status: 'AUTHENTIC & ACTIVE',
    type: docType || 'NOC - Grid Interconnection',
    createdAt: new Date()
  };

  try {
    const db = await getDatabase();
    if (db) {
      await db.collection('noc_certificates').insertOne(newDoc);
    } else {
      initialDocuments.unshift(newDoc);
    }

    return res.status(201).json({
      status: 'success',
      message: 'Digital NOC registered successfully.',
      data: newDoc
    });
  } catch (err) {
    return res.status(500).json({ status: 'error', message: err.message });
  }
}
