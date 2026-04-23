-- AlgoWire Database Schema
-- Run this SQL to set up the database

CREATE DATABASE IF NOT EXISTS algowire_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE algowire_blog;

-- Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    color VARCHAR(7) DEFAULT '#e63946',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Authors
CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    avatar VARCHAR(500) DEFAULT NULL,
    bio TEXT,
    github VARCHAR(255) DEFAULT NULL,
    twitter VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL UNIQUE,
    content LONGTEXT,
    excerpt TEXT,
    cover_image VARCHAR(500) DEFAULT NULL,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    read_time INT DEFAULT 5,
    status ENUM('draft', 'published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Comments
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(255),
    content TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Admins
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================
-- SEED DATA
-- =====================

-- Categories
INSERT INTO categories (name, slug, color) VALUES
('JavaScript', 'javascript', '#e63946'),
('DevOps', 'devops', '#e63946'),
('Security', 'security', '#e63946'),
('Python', 'python', '#e63946'),
('React', 'react', '#e63946'),
('Cloud', 'cloud', '#e63946');

-- Authors
INSERT INTO authors (name, email, avatar, bio, github) VALUES
('Mohamed Dghar', 'mohamed@algowire.com', 'https://ui-avatars.com/api/?name=Mohamed+Dghar&background=e63946&color=fff&size=128', 'Full-stack developer and lead architect of AlgoWire. Passionate about systems design and modern web technologies.', 'dgharmohamed'),
('Aymane Salmoune', 'aymane@algowire.com', 'https://ui-avatars.com/api/?name=Aymane+Salmoune&background=e63946&color=fff&size=128', 'Software engineer specialized in backend systems and infrastructure scaling.', 'aymanesalmoune');

-- Admin (password: admin123)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Articles
INSERT INTO articles (title, slug, content, excerpt, cover_image, category_id, author_id, is_featured, views, read_time, status) VALUES
(
    'Building Reactive UIs at Scale: React 19 Concurrency Deep Dive',
    'react-19-concurrency-deep-dive',
    '<p>An in-depth exploration of React 19''s concurrent rendering model, transitions API, and how to leverage them for buttery-smooth user experiences even under heavy load.</p><h2>Understanding Concurrent Rendering</h2><p>React 19 introduces a fundamentally new way of thinking about rendering. Instead of synchronous, blocking renders, React can now prepare multiple versions of your UI simultaneously, choosing the best moment to commit changes to the screen.</p><h2>The Transitions API</h2><p>The <code>useTransition</code> hook allows you to mark certain state updates as non-urgent. This means React can interrupt these updates to handle more pressing user interactions, like typing in an input field.</p><pre><code>const [isPending, startTransition] = useTransition();\n\nfunction handleSearch(query) {\n  startTransition(() => {\n    setSearchResults(filterData(query));\n  });\n}</code></pre><h2>Suspense Boundaries</h2><p>Suspense boundaries work hand-in-hand with concurrent features, allowing you to declaratively specify loading states while React prepares content in the background.</p><h2>Performance Implications</h2><p>In our benchmarks, concurrent rendering reduced Time to Interactive (TTI) by up to 40% on complex dashboards with hundreds of interactive components.</p>',
    'An in-depth exploration of React 19''s concurrent rendering model, transitions API, and how to leverage them for buttery-smooth user experiences even under heavy load.',
    'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=500&fit=crop',
    1, 1, 1, 14823, 12, 'published'
),
(
    'Zero-Trust Architecture: Redesigning Your Cloud Security Perimeter',
    'zero-trust-architecture-cloud-security',
    '<p>The traditional castle-and-moat approach to network security is dead. In an era of remote work, cloud-native applications, and sophisticated threat actors, Zero-Trust Architecture (ZTA) has emerged as the gold standard.</p><h2>Core Principles</h2><p>Never trust, always verify. Every request, regardless of origin, must be authenticated, authorized, and encrypted before access is granted.</p><h2>Implementation Strategy</h2><p>Start with identity: implement strong multi-factor authentication across all services. Then move to micro-segmentation of your network, ensuring that compromising one service doesn''t give attackers lateral movement.</p><h2>Tools and Technologies</h2><p>Solutions like HashiCorp Vault for secrets management, Istio for service mesh, and BeyondCorp for zero-trust network access form the backbone of a modern ZTA implementation.</p>',
    'The traditional castle-and-moat approach to network security is dead. Learn how to implement Zero-Trust Architecture for your cloud infrastructure.',
    'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=500&fit=crop',
    3, 1, 1, 9241, 15, 'published'
),
(
    'Kubernetes at the Edge: Running Distributed Workloads on K3s',
    'kubernetes-edge-k3s-distributed-workloads',
    '<p>Edge computing demands lightweight, efficient orchestration. K3s, a certified Kubernetes distribution built for IoT and edge computing, delivers the full Kubernetes API in a binary under 100MB.</p><h2>Why K3s?</h2><p>Traditional Kubernetes clusters require significant resources. K3s strips away cloud-provider-specific code, legacy features, and optional components to create a lean, production-ready distribution.</p><h2>Architecture for Edge</h2><p>Deploy K3s servers in regional data centers with K3s agents running on edge devices. Use Kilo or WireGuard for secure mesh networking between nodes.</p><h2>Real-World Use Cases</h2><p>From retail point-of-sale systems to industrial IoT sensors, K3s is powering the next generation of distributed applications at the edge.</p>',
    'Edge computing demands lightweight orchestration. Learn how K3s brings Kubernetes to resource-constrained environments.',
    'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&h=500&fit=crop',
    2, 2, 1, 7502, 10, 'published'
),
(
    'Advanced Python Metaprogramming: Decorators, Metaclasses, and Beyond',
    'python-metaprogramming-decorators-metaclasses',
    '<p>Python''s metaprogramming capabilities allow you to write code that generates or modifies other code at runtime. This guide explores advanced techniques that can dramatically reduce boilerplate and increase expressiveness.</p><h2>Decorator Patterns</h2><p>Beyond simple function decorators, we explore class decorators, decorator factories, and the powerful <code>functools.wraps</code> pattern for preserving function metadata.</p><h2>Metaclasses</h2><p>Metaclasses are the ''classes of classes''. They control how classes themselves are created, allowing you to enforce interfaces, register classes automatically, or transform class definitions.</p>',
    'Explore Python''s powerful metaprogramming features including decorators, metaclasses, and runtime code generation.',
    'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&h=500&fit=crop',
    4, 1, 0, 5320, 18, 'published'
),
(
    'GraphQL Federation: Building a Unified API Gateway',
    'graphql-federation-unified-api-gateway',
    '<p>As microservice architectures grow, managing multiple GraphQL schemas becomes increasingly complex. Apollo Federation provides an elegant solution by composing multiple GraphQL services into a single, unified graph.</p><h2>Schema Composition</h2><p>Each service defines its own schema and resolvers. The gateway automatically composes these into a unified supergraph, handling cross-service relationships transparently.</p>',
    'Learn how Apollo Federation helps you compose multiple GraphQL services into a single, unified API gateway.',
    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=500&fit=crop',
    1, 2, 0, 4187, 8, 'published'
),
(
    'Container Security Best Practices: From Build to Runtime',
    'container-security-best-practices',
    '<p>Securing containerized applications requires a defense-in-depth approach that spans the entire lifecycle from building images to runtime monitoring.</p><h2>Image Security</h2><p>Use minimal base images like distroless or Alpine. Scan images with tools like Trivy or Snyk. Never run containers as root.</p><h2>Runtime Security</h2><p>Implement Pod Security Policies, use read-only file systems where possible, and deploy runtime security tools like Falco for anomaly detection.</p>',
    'A comprehensive guide to securing containerized applications from build pipeline through production runtime.',
    'https://images.unsplash.com/photo-1563986768609-322da13575f2?w=800&h=500&fit=crop',
    3, 1, 0, 6890, 14, 'published'
),
(
    'WebAssembly Beyond the Browser: Server-Side Wasm with Wasmtime',
    'webassembly-server-side-wasmtime',
    '<p>WebAssembly is breaking free from the browser. With runtimes like Wasmtime, Wasm modules can now run on servers, edge nodes, and embedded devices with near-native performance.</p><h2>Why Server-Side Wasm?</h2><p>Wasm provides sandboxed execution, language-agnostic deployment, and incredibly fast cold starts — making it ideal for serverless and edge computing.</p>',
    'WebAssembly breaks free from the browser. Explore server-side Wasm with Wasmtime for high-performance, sandboxed execution.',
    'https://images.unsplash.com/photo-1518432031352-d6fc5c10da5a?w=800&h=500&fit=crop',
    1, 1, 0, 3456, 11, 'published'
),
(
    'Terraform at Scale: Managing Multi-Cloud Infrastructure',
    'terraform-multi-cloud-infrastructure',
    '<p>Managing infrastructure across AWS, GCP, and Azure with Terraform requires careful module design, state management, and CI/CD integration.</p><h2>Module Architecture</h2><p>Design reusable modules that abstract provider-specific details while maintaining flexibility for per-environment customization.</p>',
    'Master Terraform for multi-cloud infrastructure management with reusable modules and scalable state management.',
    'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&h=500&fit=crop',
    6, 2, 0, 8234, 16, 'published'
),
(
    'The Art of Code Review: Building a Culture of Engineering Excellence',
    'art-of-code-review-engineering-excellence',
    '<p>Code reviews are more than bug-catching exercises. When done well, they build shared understanding, mentor junior engineers, and establish team coding standards.</p><h2>Review Principles</h2><p>Focus on correctness, readability, and maintainability. Provide actionable, specific feedback rather than vague suggestions.</p>',
    'Transform your code review process from a bottleneck into a force multiplier for engineering excellence.',
    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=500&fit=crop',
    1, 1, 0, 11200, 9, 'published'
),
(
    'Rust for Systems Programming: Memory Safety Without the Overhead',
    'rust-systems-programming-memory-safety',
    '<p>Rust delivers on its promise of memory safety without garbage collection, making it an ideal choice for systems programming where performance and reliability are paramount.</p><h2>Ownership Model</h2><p>Rust''s ownership system ensures memory safety at compile time, eliminating entire classes of bugs like use-after-free, double-free, and data races.</p>',
    'Explore how Rust''s ownership model delivers memory safety without garbage collection overhead.',
    'https://images.unsplash.com/photo-1504639725590-34d0984388bd?w=800&h=500&fit=crop',
    1, 2, 0, 9870, 13, 'published'
),
(
    'CI/CD Pipeline Design Patterns for Modern Teams',
    'cicd-pipeline-design-patterns',
    '<p>A well-designed CI/CD pipeline is the backbone of modern software delivery. This article explores proven patterns for building reliable, fast, and secure pipelines.</p><h2>Pipeline Stages</h2><p>From commit to production: linting, unit tests, integration tests, security scanning, staging deployment, and production rollout with canary releases.</p>',
    'Proven patterns for building reliable, fast, and secure CI/CD pipelines for modern development teams.',
    'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=800&h=500&fit=crop',
    2, 2, 0, 7650, 12, 'published'
),
(
    'OAuth 2.0 and OpenID Connect: A Complete Implementation Guide',
    'oauth2-openid-connect-implementation-guide',
    '<p>Implementing authentication and authorization correctly is critical for application security. This guide walks through OAuth 2.0 and OpenID Connect from theory to production-ready implementation.</p>',
    'A comprehensive guide to implementing OAuth 2.0 and OpenID Connect for secure authentication and authorization.',
    'https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?w=800&h=500&fit=crop',
    3, 1, 0, 6540, 20, 'published'
);

-- Comments
INSERT INTO comments (article_id, author_name, author_email, content) VALUES
(1, 'DevFan42', 'fan@example.com', 'Great deep dive into React 19 concurrency! The useTransition examples were especially helpful.'),
(1, 'ReactLover', 'react@example.com', 'Been waiting for this kind of breakdown. The performance benchmarks are impressive.'),
(1, 'CodeNinja', 'ninja@example.com', 'How does this compare to Vue 3''s composition API for managing complex state?'),
(2, 'SecOps_Pro', 'secops@example.com', 'Zero Trust is the way forward. We implemented BeyondCorp last year and saw immediate improvements.'),
(3, 'EdgeRunner', 'edge@example.com', 'K3s has been a game changer for our IoT fleet management. Great overview!');
