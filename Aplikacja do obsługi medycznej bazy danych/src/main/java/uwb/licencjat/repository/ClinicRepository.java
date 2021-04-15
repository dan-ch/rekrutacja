package uwb.licencjat.repository;

import org.springframework.data.domain.Sort;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;
import uwb.licencjat.model.Clinic;

import java.util.List;
import java.util.Optional;

@Repository
public interface ClinicRepository extends JpaRepository<Clinic, Long> {

    @Query("SELECT c FROM Clinic c WHERE UPPER(c.name) LIKE UPPER(CONCAT('%', :name, '%')) OR UPPER(c.city) LIKE " +
            "UPPER(CONCAT('%', :city, '%')) OR UPPER(c.province) LIKE UPPER(CONCAT('%', :province, '%')) " +
            "OR UPPER(c.clinicCategory.name) LIKE UPPER(CONCAT('%', :category, '%'))")
    List<Clinic> findAllByNameCityProvinceCategoryContains(
            @Param("name") String name, @Param("city") String city, @Param("province") String province,
            @Param("category") String category, Sort sort);

    boolean existsByNameIgnoreCase(String name);

    Optional<Clinic> findByNameIgnoreCase(String name);
}
