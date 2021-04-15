package uwb.licencjat.model;

import org.hibernate.validator.constraints.pl.PESEL;
import org.springframework.format.annotation.DateTimeFormat;
import uwb.licencjat.enums.Gender;
import uwb.licencjat.enums.Province;
import uwb.licencjat.validator.UniqueEmail;
import uwb.licencjat.validator.UniquePesel;

import javax.persistence.*;
import javax.validation.constraints.*;
import java.util.Calendar;
import java.util.HashSet;
import java.util.Set;

@Entity
@Table(name = "users")
public class User{

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private long id;
    @NotBlank(message = "Email nie może być pusty")
    @Email(message = "Podany adres email nie jest poprawny")
    @Column(unique = true)
    //@UniqueEmail
    private String email;
    private String password;
    @NotBlank(message = "Imię nie może być puste")
    private String firstName;
    @NotBlank(message = "Nazwisko nie może być puste")
    private String lastName;
    @NotBlank(message = "To pole nie może być puste")
    @PESEL(message = "Podany PESEL nie jest poprawny")
    @Column(length = 11, unique = true)
    //@UniquePesel
    private String pesel;
    @Temporal(TemporalType.DATE)
    @DateTimeFormat(pattern = "dd.MM.yyyy", iso = DateTimeFormat.ISO.DATE)
    private Calendar birthDate;
    @NotBlank(message = "Nazwa miasta nie może być pusta")
    private String city;
    @NotBlank(message = "Nazwa ulicy nie może być pusta")
    private String street;
    @Pattern(regexp = "[0-9]{2}-[0-9]{3}", message = "Kod pocztowy musi mieć format XX-XXX")
    @Column(length = 6)
    private String postCode;
    @NotNull
    @Enumerated(EnumType.STRING)
    private Province province;
    @Enumerated(EnumType.STRING)
    private Gender gender;
    @NotEmpty(message = "Należy wybrać role dla użytkownika")
    @ManyToMany(fetch = FetchType.EAGER)
    private Set<UserRole> roles = new HashSet<>();

    public User() {
    }

    public long getId() {
        return id;
    }

    public void setId(long id) {
        this.id = id;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lastName) {
        this.lastName = lastName;
    }

    public String getPesel() {
        return pesel;
    }

    public void setPesel(String pesel) {
        this.pesel = pesel;
    }

    public Calendar getBirthDate() {
        return birthDate;
    }

    public void setBirthDate(Calendar birthDate) {
        this.birthDate = birthDate;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getStreet() {
        return street;
    }

    public void setStreet(String street) {
        this.street = street;
    }

    public String getPostCode() {
        return postCode;
    }

    public void setPostCode(String postCode) {
        this.postCode = postCode;
    }

    public Province getProvince() {
        return province;
    }

    public void setProvince(Province province) {
        this.province = province;
    }

    public Gender getGender() {
        return gender;
    }

    public void setGender(Gender gender) {
        this.gender = gender;
    }

    public Set<UserRole> getRoles() {
        return roles;
    }

    public void setRoles(Set<UserRole> roles) {
        this.roles = roles;
    }

}
