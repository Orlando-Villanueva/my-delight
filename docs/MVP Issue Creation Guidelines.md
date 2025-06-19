# MVP Issue Creation Guidelines

## 🎯 Over-Engineering Prevention Framework

**Goal**: Create right-sized issues for a 3-table Bible reading habit tracker MVP, not enterprise banking software.

## 📋 Before Creating Any Issue

### **MVP Reality Check Questions**
Ask these questions BEFORE writing detailed requirements:

1. **Scale Reality**: "Am I solving for 10 users or 10,000 users?"
2. **Complexity Reality**: "Am I solving for 3 tables or 300 tables?"
3. **Risk Reality**: "What's the actual worst-case scenario?"
4. **Time Reality**: "Is this worth X hours vs. shipping user features?"

### **Scope Decision Tree**

```
Is this issue critical for MVP launch? 
├─ NO → Defer to Post-MVP backlog
└─ YES → Continue

Does Laravel/framework handle this already?
├─ YES → Use framework solution, minimal testing
└─ NO → Continue

Is this solving a problem we actually have?
├─ NO → Reconsider necessity
└─ YES → Continue

Will users notice if this isn't perfect?
├─ NO → Simplify to bare minimum
└─ YES → Right-size the scope
```

## ⚡ Issue Sizing Framework

### **🟢 Right-Sized (Good)**
- **Time**: 30 minutes - 4 hours
- **Scope**: Solves one specific problem
- **Testing**: Smoke test + essential validation
- **Documentation**: Inline comments + basic notes

**Example**: "Basic migration rollback verification (30 minutes)"

### **🟡 Watch Zone (Review Needed)**
- **Time**: 4+ hours - 1 day
- **Scope**: Multiple related problems
- **Testing**: Comprehensive scenarios
- **Documentation**: Detailed specifications

**Trigger Review**: Ask "Can this be simplified for MVP?"

### **🔴 Over-Engineered (Simplify)**
- **Time**: 1+ days
- **Scope**: Enterprise-level scenarios
- **Testing**: Every possible edge case
- **Documentation**: Extensive matrices

**Red Flags**:
- "Cross-environment testing matrices"
- "Comprehensive validation frameworks"
- "Production-scale data scenarios"
- "Enterprise-level compliance"

## 🎯 MVP-Specific Guidelines

### **Database/Backend Issues**
- **✅ Do**: Test basic functionality works
- **❌ Don't**: Test every possible failure scenario
- **✅ Do**: Verify core constraints work
- **❌ Don't**: Build comprehensive validation matrices

### **Frontend/UI Issues**
- **✅ Do**: Ensure mobile responsiveness
- **❌ Don't**: Pixel-perfect cross-browser testing
- **✅ Do**: Test core user journey
- **❌ Don't**: Test every interaction edge case

### **Testing Issues**
- **✅ Do**: Essential functionality verification
- **❌ Don't**: Comprehensive test automation suites
- **✅ Do**: Smoke tests for critical paths
- **❌ Don't**: Enterprise-level testing frameworks

## 📏 Time Boxing Rules

### **Hard Limits by Category**
- **Database Tasks**: Max 4 hours
- **UI Components**: Max 6 hours  
- **Feature Implementation**: Max 1 day
- **Testing/Validation**: Max 2 hours

### **Automatic Simplification Triggers**
If estimated time exceeds limits:
1. **Identify core requirement**
2. **Remove enterprise-level patterns**
3. **Focus on MVP user value**
4. **Defer advanced features to post-MVP**

## 🚀 Issue Template

```markdown
## [Issue Type]: [Brief Description]

**MVP Context**: Bible reading habit tracker (3 tables, personal use)
**Time Estimate**: [Must be realistic for MVP scope]
**User Value**: [How does this help users track Bible reading?]

### Essential Requirements
- [ ] [Minimum needed for MVP]
- [ ] [Core functionality only]

### Future Enhancements (Post-MVP)
- [ ] [Nice-to-have features]
- [ ] [Enterprise-level improvements]

### Reality Check
- **Framework Coverage**: [What does Laravel handle automatically?]
- **Actual Risk**: [What's the real worst-case scenario?]
- **User Impact**: [Will users notice if this isn't perfect?]
```

## 🎲 Quick Decision Heuristics

### **The 80/20 Rule**
- **80% of value** from **20% of features**
- **80% of risk coverage** from **20% of testing**
- **80% of functionality** from **20% of complexity**

### **The "Personal Project" Test**
- "Would I spend this much time on my personal hobby project?"
- "Is this solving a problem I actually have?"
- "Am I building for imaginary scale?"

### **The "Ship It" Test**
- "Can users accomplish their goal without this?"
- "Does this block MVP launch?"
- "Is this premature optimization?"

## 📊 Success Metrics

### **Good Issue Indicators**
- Completed in estimated time
- Solves specific user problem
- Can be tested quickly
- Clear success criteria

### **Over-Engineering Indicators**
- Takes 2x+ estimated time
- Solves theoretical problems
- Requires extensive testing
- Success criteria unclear

## 🔄 Continuous Improvement

### **After Each Issue**
- **Time Accuracy**: Was estimate realistic?
- **Scope Creep**: Did requirements expand during work?
- **User Value**: Did this help actual users?
- **Simplification**: What could have been simpler?

This framework should be applied to **every issue** during MVP development to maintain appropriate scope and velocity. 