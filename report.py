import os
import pandas as pd
import mysql.connector
import matplotlib.pyplot as plt
import seaborn as sns

# Ensure reports directory exists
if not os.path.exists('reports'):
    os.makedirs('reports')

# Establish MySQL connection
conn = mysql.connector.connect(
    host="localhost",  # Replace with your host
    user="root",  # Replace with your username
    password="Sekhar@1629",  # Replace with your password
    database="srms"  # Replace with your database name
)

# Query to get results for analysis (with column disambiguation)
query = """
SELECT result.rno, students.name, result.marks, result.class, result.percentage 
FROM result
JOIN students ON result.rno = students.rno
"""
df = pd.read_sql(query, conn)

# Close the connection
conn.close()

# Top Performers (Top 5 students with highest marks)
top_performers = df.sort_values(by="marks", ascending=False).head(5)

# Class-wise Average Marks
class_avg = df.groupby('class')['marks'].mean()

# Students Who Failed (less than 40%)
failed_students = df[df['percentage'] < 40]

# Save Top Performers Bar Plot
plt.figure(figsize=(10, 6))
sns.barplot(x='name', y='marks', data=top_performers, palette='Blues_d')
plt.title('Top Performers in Class')
plt.xlabel('Student Name')
plt.ylabel('Marks')
plt.xticks(rotation=45)
plt.tight_layout()
plt.savefig('reports/top_performers.png')  # Save as image

# Save Class-wise Average Marks Bar Plot
plt.figure(figsize=(10, 6))
sns.barplot(x=class_avg.index, y=class_avg.values, palette='coolwarm')
plt.title('Class-wise Average Marks')
plt.xlabel('Class')
plt.ylabel('Average Marks')
plt.tight_layout()
plt.savefig('reports/class_avg.png')  # Save as image

# Save Failed Students Pie Chart
failed_count = len(failed_students)
passed_count = len(df) - failed_count
labels = ['Failed', 'Passed']
sizes = [failed_count, passed_count]
plt.figure(figsize=(6, 6))
plt.pie(sizes, labels=labels, autopct='%1.1f%%', colors=['#FF6666', '#66FF66'])
plt.title('Pass/Fail Distribution')
plt.savefig('reports/pass_fail_distribution.png')  # Save as image
