from faker import Faker
fake = Faker(['it_IT'])


f = open("text.txt", "a")
txt="('{}','{}','{}','{}','{}','{}'),\n"
for _ in range(100):
    f.write(txt.format(fake.ascii_free_email(),fake.lexify(text='????????'),fake.first_name(),fake.last_name(),fake.date(),fake.random_element(elements=('M', 'F', 'ND'))))
    


f.close()
