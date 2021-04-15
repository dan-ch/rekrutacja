package uwb.licencjat.model;

import uwb.licencjat.enums.Province;

import javax.persistence.*;
import javax.validation.constraints.NotBlank;
import javax.validation.constraints.NotNull;
import javax.validation.constraints.Pattern;
import javax.validation.constraints.Size;

@Entity
@Table(name = "clinics")
public class Clinic {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private long id;
    @Size(min = 5, message = "Nazwa placówki musi mieć minimum 5 znaków")
    @Column(unique = true)
    private String name;
    @Column(length = 2048)
    @Size(max = 2048, message = "Opis może mieć maksymalnie 2048 znaków")
    private String description;
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
    @ManyToOne
    private ClinicCategory clinicCategory;

    public Clinic() {
    }

    public Clinic(String name, String description, String city, String street, String postCode,
                  ClinicCategory clinicCategory) {
        this.name = name;
        this.description = description;
        this.city = city;
        this.street = street;
        this.postCode = postCode;
        this.clinicCategory = clinicCategory;
    }

    public long getId() {
        return id;
    }

    public void setId(long id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
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

    public ClinicCategory getClinicCategory() {
        return clinicCategory;
    }

    public void setClinicCategory(ClinicCategory clinicCategory) {
        this.clinicCategory = clinicCategory;
    }

    public Province getProvince() {
        return province;
    }

    public void setProvince(Province province) {
        this.province = province;
    }
}

